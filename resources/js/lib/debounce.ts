export function debounce(callback, delay = 200, immediate = false) {
    let timeoutId;

    return function (...args) {
        const callNow = immediate && !timeoutId;

        clearTimeout(timeoutId);

        timeoutId = setTimeout(() => {
            timeoutId = null;

            if (!immediate) {
                callback(...args);
            }
        }, delay);

        if (callNow) {
            callback(...args);
        }
    };
}
