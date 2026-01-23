import { formatPrice as formatPriceUtil } from '@/utils/formatPrice'

export const usePrice = () => {
  const formatPrice = (value, locale = 'fr-FR', currency = 'EUR') => {
    return formatPriceUtil(value, locale, currency)
  }

  return { formatPrice }
}
