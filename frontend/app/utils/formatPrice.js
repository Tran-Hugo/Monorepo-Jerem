export function formatPrice(value, locale = 'fr-FR', currency = 'EUR') {
  if (value == null) return ''
  return new Intl.NumberFormat(locale, {
    style: 'currency',
    currency
  }).format(value / 100)
}