import * as en from "~/locales/en";
import { createI18n } from 'vue-i18n';

export const useI18n = createI18n({
  legacy: false,
  locale: "en",
  messages: {
    en,
  },
})

export default defineI18nConfig(() => ({
  legacy: false,
  locale: "en",
  messages: {
    en,
  },
}));
