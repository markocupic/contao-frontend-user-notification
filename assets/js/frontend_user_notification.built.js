import {createApp} from 'vue';
import IdlenessDetector from './modules/idleness_detector.js';

const modules = document.querySelectorAll('.frontend-user-notification-list');

for (const module of modules) {

    if (document.getElementById(module.getAttribute('id'))) {
        const appSelector = `#${module.getAttribute('id')}`;
        const idlenessDetector = new IdlenessDetector(60000);
        idlenessDetector.start();

        const app = createApp({
            data() {
                return {
                    intervalId: null,
                    items: [],
                    ids: [],
                }
            },
            mounted() {
                window.setTimeout(async () => {
                    await this.loadItems();
                }, 1000);

                this.intervalId = window.setInterval(async () => {
                    if (idlenessDetector.isIdle() === false) {
                        await this.loadItems();
                    } else {
                        console.log(`User is idle for ${idlenessDetector.getIdleTime()} ms. Stop loading messages`)
                    }
                }, 15000);
            },
            methods: {
                loadItems: async function loadItems() {
                    const response = await fetch('_frontend_user_notification/get');

                    if (!response.ok) {
                        throw new Error(`Response status: ${response.status}`);
                    }

                    const json = await response.json();

                    if (json.status === 'success') {
                        this.items = json.data;
                        await this.$nextTick();
                        const toasts = document.querySelectorAll(appSelector + ' .toast');

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
                    await fetch(`_frontend_user_notification/tag_as_read/${id}`);
                    await this.loadItems();
                }
            }
        });
        app.config.compilerOptions.delimiters = ['[[', ']]'];
        app.mount(appSelector);
    }
}
