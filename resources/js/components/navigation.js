// resources/js/components/navigation.js

export default function navigationComponent(config) {
    return {
        // State
        isScrolled: false,
        notificationOpen: false,
        profileOpen: false,
        loading: false,
        
        // Data from backend
        userName: config.userName,
        userInitial: config.userInitial,
        unreadCount: config.unreadCount,
        notifications: config.notifications,
        
        // Routes
        profileEditRoute: config.profileEditRoute,
        notificationsIndexRoute: config.notificationsIndexRoute,
        markAllReadRoute: config.markAllReadRoute,
        markReadRoute: config.markReadRoute,
        logoutRoute: config.logoutRoute,

        // CSRF Token
        csrfToken: config.csrfToken,
        
        // Lifecycle
        init() {
            // update scroll state on load + scroll
            this.isScrolled = window.scrollY > 10;
            window.addEventListener("scroll", () => {
                this.isScrolled = window.scrollY > 10;
            });
        },
        
        // 🔔 Notifications
        async toggleNotifications() {
            this.notificationOpen = !this.notificationOpen;
            this.profileOpen = false; // close profile if open
            if (this.notificationOpen && this.unreadCount > 0) {
                await this.markAllAsRead();
            }
        },
        
        async markAsRead(notificationId) {
            if (this.loading) return;
            this.loading = true;
            try {
                const response = await fetch(this.markReadRoute.replace(':id', notificationId), {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    const notification = this.notifications.find(n => n.id === notificationId);
                    if (notification) {
                        notification.is_read = true;
                        this.updateUnreadCount();
                    }
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async markAllAsRead() {
            if (this.loading) return;
            this.loading = true;
            try {
                const response = await fetch(this.markAllReadRoute, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    this.notifications.forEach(n => n.is_read = true);
                    this.updateUnreadCount();
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            } finally {
                this.loading = false;
            }
        },
        
        updateUnreadCount() {
            this.unreadCount = this.notifications.filter(n => !n.is_read).length;
        },

        // 👤 Profile
        toggleProfile() {
            this.profileOpen = !this.profileOpen;
            this.notificationOpen = false; // close notifications if open
        },
        
        // 🚪 Logout
        logout() {
            if (confirm('Are you sure you want to log out?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = this.logoutRoute;
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = this.csrfToken;
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    }
}
