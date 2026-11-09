
export default () => ({
    open: false,
    items: [],
    unread: 0,
    loading: false,
    busy: false,
    error: false,
    timer: null,

    init() {
        this.load();

        // Kiểm tra thông báo mới mỗi 20 giây
        this.timer = window.setInterval(() => {
            if (!document.hidden) {
                this.load();
            }
        }, 20000);
    },

    destroy() {
        if (this.timer) {
            window.clearInterval(this.timer);
        }
    },

    toggle() {
        this.open = !this.open;

        if (this.open) {
            this.load();
        }
    },

    async load() {
        if (this.loading) return;

        this.loading = true;

        try {
            const response = await fetch(
                this.$el.dataset.feedUrl,
                {
                    headers: {
                        Accept: 'application/json',
                    },
                    credentials: 'same-origin',
                    cache: 'no-store',
                }
            );

            if (!response.ok) {
                throw new Error('Không tải được thông báo');
            }

            const result = await response.json();

            this.items = result.items ?? [];
            this.unread = result.unread ?? 0;
            this.error = false;
        } catch (error) {
            this.error = true;
        } finally {
            this.loading = false;
        }
    },

    async post(url) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            credentials: 'same-origin',
        });

        if (!response.ok) {
            throw new Error('Không thể cập nhật thông báo');
        }

        return await response.json();
    },

    async openItem(item) {
        if (this.busy) return;

        if (item.read) {
            window.location.assign(item.url);
            return;
        }

        this.busy = true;

        try {
            const url = this.$el.dataset.readBase
                + '/'
                + encodeURIComponent(item.id)
                + '/read';

            const result = await this.post(url);

            window.location.assign(result.url);
        } catch (error) {
            this.error = true;
            this.busy = false;
        }
    },

    async markAll() {
        if (this.busy || this.unread === 0) return;

        this.busy = true;

        try {
            await this.post(this.$el.dataset.readAllUrl);
            await this.load();
        } catch (error) {
            this.error = true;
        } finally {
            this.busy = false;
        }
    },
});
