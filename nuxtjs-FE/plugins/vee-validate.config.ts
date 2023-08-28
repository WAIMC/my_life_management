import { defineRule } from "vee-validate";

export default defineNuxtPlugin(() => {
  /**
   * Define rule for required validation
   *
   * @param any value
   * @param [any] [target]
   * @param FieldValidationMetaInfo ctx
   * @return boolean | string
   */
  defineRule("required", (value: any, [target]: [string], ctx) => {
    if (value === null || value === undefined || value === false)
      return useNuxtApp().$i18n.t(
        "UserManual.VeeValidate.ErrorMessage.REQUIRED",
        { field: ctx.name }
      );

    if (typeof value === "string" && (value.trim() === "" || !value.length))
      return useNuxtApp().$i18n.t(
        "UserManual.VeeValidate.ErrorMessage.REQUIRED",
        { field: ctx.name }
      );

    if (typeof value === "number" && value <= 0)
      return useNuxtApp().$i18n.t(
        "UserManual.VeeValidate.ErrorMessage.REQUIRED",
        { field: ctx.name }
      );

    if (typeof value === "object" && !value.length)
      return useNuxtApp().$i18n.t(
        "UserManual.VeeValidate.ErrorMessage.REQUIRED",
        { field: ctx.name }
      );

    return true;
  });

  /**
   * Define rule for email validation
   *
   * @param string value
   * @param [string] [target]
   * @param FieldValidationMetaInfo ctx
   * @return boolean | string
   */
  defineRule("email", (value: string, [target]: [string], ctx) => {
    // Field is empty, should pass
    if (!value || !value.length) return true;

    // Check if email
    if (!/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/.test(value))
      return useNuxtApp().$i18n.t("UserManual.VeeValidate.ErrorMessage.EMAIL", {
        field: ctx.name,
      });

    return true;
  });

  /**
   * Define rule for min max validation
   *
   * @param string|number value
   * @param [string] [min] and [max]
   * @param FieldValidationMetaInfo ctx
   * @return boolean | string
   */
  defineRule("minMax", (value: string | number, [min, max]: string, ctx) => {
    // The field is empty so it should pass
    if (!value || (typeof value === "string" && !value.length)) return true;

    // Check value type string|number greater than min
    if (
      (typeof value === "string" && value.length < +min) ||
      (typeof value === "number" && +value < +min)
    )
      return useNuxtApp().$i18n.t("UserManual.VeeValidate.ErrorMessage.MIN", {
        field: ctx.name,
        min,
      });

    // Check value type string|number less than max
    if (
      (typeof value === "string" && value.length > +max) ||
      (typeof value === "number" && +value > +max)
    )
      return useNuxtApp().$i18n.t("UserManual.VeeValidate.ErrorMessage.MAX", {
        field: ctx.name,
        max,
      });

    return true;
  });

  /**
   * Define rule for min validation
   *
   * @param string|number value
   * @param [string] [min]
   * @param FieldValidationMetaInfo ctx
   * @return boolean | string
   */
  defineRule("min", (value: string | number, [min]: string, ctx) => {
    // The field is empty so it should pass
    if (!value || (typeof value === "string" && !value.length)) return true;

    // Check value type string|number greater than min
    if (
      (typeof value === "string" && value.length < +min) ||
      (typeof value === "number" && +value < +min)
    )
      return useNuxtApp().$i18n.t("UserManual.VeeValidate.ErrorMessage.MIN", {
        field: ctx.name,
        min,
      });

    return true;
  });

  /**
   * Define rule for max validation
   *
   * @param string|number value
   * @param [string] [max]
   * @param FieldValidationMetaInfo ctx
   * @return boolean | string
   */
  defineRule("max", (value: string | number, [max]: string, ctx) => {
    // The field is empty so it should pass
    if (!value || (typeof value === "string" && !value.length)) return true;

    // Check value type string|number less than max
    if (
      (typeof value === "string" && value.length > +max) ||
      (typeof value === "number" && +value > +max)
    )
      return useNuxtApp().$i18n.t("UserManual.VeeValidate.ErrorMessage.MAX", {
        field: ctx.name,
        max,
      });

    return true;
  });

  /**
   * Define rule for confirmed validation
   *
   * @param string value
   * @param [string] [target]
   * @param FieldValidationMetaInfo ctx
   * @return boolean | string
   */
  defineRule("confirmed", (value: string, [target]: string, ctx) => {
    if (value === ctx.form[target]) return true;

    return useNuxtApp().$i18n.t(
      "UserManual.VeeValidate.ErrorMessage.CONFIRMED",
      { field: target }
    );
  });

  /**
   * Define rule for alphanumeric validation
   *
   * @param string value
   * @param [string] [target]
   * @param FieldValidationMetaInfo ctx
   * @return boolean | string
   */
  defineRule("alphanumeric", (value: any, [target]: [string], ctx) => {
    if (!/^[a-z0-9]+$/i.test(value))
      return useNuxtApp().$i18n.t(
        "UserManual.VeeValidate.ErrorMessage.ALPHANUMERIC",
        {
          field: ctx.name,
        }
      );

    return true;
  });

  /**
   * Define rule for digits validation
   *
   * @param string value
   * @param [string] [target]
   * @param FieldValidationMetaInfo ctx
   * @return boolean | string
   */
  defineRule("digits", (value: any, [target]: [string], ctx) => {
    if (!/^\d*$/i.test(value))
      return useNuxtApp().$i18n.t(
        "UserManual.VeeValidate.ErrorMessage.DIGITS",
        {
          field: ctx.name,
        }
      );

    return true;
  });
});
