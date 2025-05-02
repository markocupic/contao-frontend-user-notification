class FrontendUserNotification {
    constructor(appSelector, opt) {
        // Defaults
        const defaults = {
            'params': {
                //'param': 'foo',
            },
        };

        // merge options and defaults
        let options = {...defaults, ...opt}

        const {createApp} = Vue

        // Instantiate vue.js application
        const app = createApp({
            data() {
                return {
                    req: 0,
                    intervalId: null,
                    items: [],
                    ids: [],
                };
            },

            mounted() {
                window.setTimeout(() => {
                    this.loadItems();
                }, 1000);

                this.intervalId = window.setInterval(async () => {
                    this.loadItems();
                }, 15000);
            },

            methods: {
                loadItems: async function loadItems(force = false) {
                    const response = await fetch('_frontend_user_notification/get');

                    if (!response.ok) {
                        throw new Error(`Response status: ${response.status}`);
                    }

                    const json = await response.json();

                    // Count requests
                    if (force === false) {
                        this.req++;
                    }

                    // Do not poll the server for a too long time.
                    if (force === false && this.req > 15) {
                        window.clearInterval(this.intervalId);
                    }

                    if (json.status === 'success') {

                        await this.$nextTick();
                        this.items = json.data;
                        await this.$nextTick();
                        const toasts = document.querySelectorAll(`${appSelector} .toast`);

                        for (const toast of toasts) {
                            // Display the toast
                            const toastInstance = new bootstrap.Toast(toast);

                            if (!toastInstance.isShown()) {
                                toastInstance.show();
                            }
                        }
                    }
                },

                tagAsRead: async function readIt(id) {
                    const response = await fetch(`_frontend_user_notification/tag_as_read/${id}`);
                    await this.loadItems(true);
                }
            }
        });

        app.config.compilerOptions.delimiters = ['[[', ']]'];
        app.mount(appSelector);
    }
}
