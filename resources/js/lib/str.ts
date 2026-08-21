/**
 * Convert a string delimited by casing, hyphens, or underscores into a space delimited string with each
 * word's first letter capitalized.
 * @example
 * ```ts
 * headline('steve_jobs') === 'Steve Jobs';
 * headline('taylor-otwell') === 'Taylor Otwell';
 * headline('EmailNotificationSent') === 'Email Notification Sent';
 * headline('XMLParser') === 'XML Parser';
 * ```
 */
export function headline(value: string): string {
    if (! value) {
        return '';
    }

    // Add whitespace between word boundaries, like lower to upper.
    const caseDelimiters = /([a-z0-9])([A-Z])/g;
    const acronymDelimiters = /([A-Z]+)([A-Z][a-z])/g;
    const specialCharacterDelimiters = /[_-]+/g;

    let headline = value
        .replace(caseDelimiters, '$1 $2')
        .replace(acronymDelimiters, '$1 $2')
        .replace(specialCharacterDelimiters, ' ');

    headline = toTitleCase(headline);

    headline = normalizeWhitespace(headline);

    return headline;
}

export function normalizeWhitespace(value: string): string {
    return value.split(/\s+/).join(' ').trim();
}

export function capitalize(value: string): string {
    return value.charAt(0).toUpperCase() + value.slice(1);
}

export function toTitleCase(value: string): string {
    return value.split(' ').map(capitalize).join(' ');
}
