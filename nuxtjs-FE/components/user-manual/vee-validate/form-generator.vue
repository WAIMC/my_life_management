<template lang="">
  <div>
    <Form :validation-schema="schema">
        <div 
          v-for="({as, name, label, ...attrs}, indx) of formSchema.fields"
          :key="indx"
        >
          <label :for="name">{{ label }}</label>
          <Field :as="as" :name="name" :id="name" v-bind="attrs" />
          <ErrorMessage :name="name" />
        </div>
        <button>Submit</button>
    </Form>
    
  </div>
</template>
<script>
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as Yup from 'yup';
import { ref } from 'vue';

export default {
  components: {
    Form,
    Field,
    ErrorMessage,
  },
  setup() {
    // Define rule global for all input in form
    const schema = Yup.object({
      email: Yup.string().required(),
      password: Yup.string().required(),
    });

    // Define rule for per input
    const formSchema = ref({
      fields: [
        {
          label: 'name',
          name: 'text',
          as: 'input',
          rules: Yup.string().required(),
        },
        {
          label: 'email',
          name: 'email',
          as: 'input',
          rules: Yup.string().email().required(),
        },
        {
          label: 'password',
          name: 'password',
          as: 'input',
          type: 'password',
          rules: Yup.string().min(6).max(10),
        },
        {
          label: 'check box',
          name: 'checkbox',
          as: 'input',
          type: 'checkbox',
        },
        {
          label: 'radio',
          name: 'radio',
          as: 'input',
          type: 'radio'
        },
        {
          label: 'text area',
          name: 'textarea',
          as: 'input',
          type: 'textarea',
        }
      ]
    });

    return { formSchema, schema }
  }
}
</script>
<style lang="">
  
</style>
