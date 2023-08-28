<template lang="">
  <div>
    <Form @submit="onSubmit">
      <Field name="email" type="email" :rules="validateEmail" />
      <ErrorMessage name="email" />
      <button>submit</button>
    </Form>
  </div>
</template>
<script>
import { Field, Form, ErrorMessage } from 'vee-validate';

export default {
  components: {
    Field,
    Form,
    ErrorMessage,
  },
  setup() {
    function onSubmit (values) {
      console.log('values :', values);
    }

    function validateEmail (value) {
      if (!value) {
        return 'this field is empty';
      }

      const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
      if (!regex.test(value)) {
        return 'This field must be a valid email';
      }

      return true;
    }
    
    return { onSubmit, validateEmail };
  }
}
</script>
<style lang="">
  
</style>