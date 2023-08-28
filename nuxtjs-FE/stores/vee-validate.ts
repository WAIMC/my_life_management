import { defineStore } from 'pinia';
import { useForm } from 'vee-validate';
import * as Yup from 'yup';

const schemaValidate = Yup.object({
  name: Yup.string().required(),
  email: Yup.string().email().required(),
  password: Yup.string().min(6).required(),
});

export const useVeeValidateStore = defineStore('veeValidate', () => {
  const { errors, defineInputBinds, handleSubmit } = useForm({
    validationSchema: schemaValidate,
  });

  const name = defineInputBinds('name');
  const email = defineInputBinds('email');
  const password = defineInputBinds('password');

  function onSubmit () {
    console.log('name :', email);
    
    return handleSubmit((values) => {
      console.log('value :', values);
    })
  }

  return {
    errors,
    name,
    email,
    password,
    onSubmit,
  }
});
