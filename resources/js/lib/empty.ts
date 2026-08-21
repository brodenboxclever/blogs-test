/**
 * "Empty" values mean values which are falsey or objects/arrays with no keys.
 */
export function isEmpty(value: any): boolean {
    // Value is falsey
    if (!value) {
        return true;

    // Value is truthy, but not an object/array
    } else if (typeof value !== 'object') {
        return false; // Catches truthy primitives
    }

    // Value is a Map or Set
    if (value instanceof Map || value instanceof Set) {
        return value.size === 0; // Catches Maps/Sets
    }

    // Value is an object/array
    return Object.keys(value).length === 0;
}

export function isEmptyArray(value: any): value is never[] {
    return Array.isArray(value) && value.length === 0;
}

export function isEmptyObject(value: any): value is Record<string, never> {
    return !! value && typeof value === 'object' && !Array.isArray(value) && Object.keys(value).length === 0;
}

/**
 * Whether the value is an array of plain, JSON-like objects containing key-value pairs.
 * @example
 * ```ts
 * isArrayOfObjects([{ id: 1 }, { id: 2 }]) === true;
 * isArrayOfObjects([{ a: 1 }]) === true;
 * isArrayOfObjects([]) === true;
 *
 * isArrayOfObjects([null]) === false;
 * isArrayOfObjects([[1, 2]]) === false;
 * isArrayOfObjects([new Date()]) === false;
 * ```
 */
export function isArrayOfObjects(value: any): value is object & Record<string, unknown>[] {
    return Array.isArray(value) && value.every((item) => item?.constructor === Object);
}

export function isNotArrayOfObjects(value: any): boolean {
    return !isArrayOfObjects(value);
}

export function toUniqueArray<T>(array: T[]): T[] {
    return [...new Set(array)];
}
