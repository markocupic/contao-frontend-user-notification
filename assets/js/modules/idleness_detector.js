export default class IdlenessDetector {
    #idleTime;
    #timeoutId = null;
    #lastActiveTime = Date.now();
    #isUserIdle = false;
    #events;

    constructor(idleTime = 30000, events = ["scroll", "mousemove", "keydown", "touchstart"]) {
        this.#idleTime = idleTime; // Time before user is considered idle
        this.#events = events;
    }

    start() {
        for (const event of this.#events) {
            window.addEventListener(event, this.resetTimer.bind(this));
        }
    }

    stop() {
        for (const event of this.#events) {
            window.removeEventListener(event, this.resetTimer.bind(this));
        }
        clearTimeout(this.#timeoutId);
    }

    isIdle() {
        return this.#isUserIdle; // Returns true if the user is idle
    }

    getIdleTime() {
        return Date.now() - this.#lastActiveTime; // Returns the idle duration in milliseconds
    }

    resetTimer() {
        // Dispatch the event
        if(this.#isUserIdle){
            const event = new CustomEvent('idlenessStatusChange', {
                detail: {
                    idleTime: this.getIdleTime(),
                    isIdle: false,
                    message: 'The user is active again.'
                }
            });
            window.dispatchEvent(event);
        }

        // Reset last active timestamp
        this.#lastActiveTime = Date.now();
        this.#isUserIdle = false;
        clearTimeout(this.#timeoutId);

        this.#timeoutId = setTimeout(() => {
            // Dispatch the event
            if(!this.#isUserIdle){
                const event = new CustomEvent('idlenessStatusChange', {
                    detail: {
                        idleTime: 0,
                        isIdle: true,
                        message: 'The user is idle now.'
                    }
                });
                window.dispatchEvent(event);
            }
            this.#isUserIdle = true;

        }, this.#idleTime);
    }
}
