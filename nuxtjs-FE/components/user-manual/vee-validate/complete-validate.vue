<template lang="">
  <div>
    <form @submit.prevent="onSubmit()" novalidate>
      <div class="field">
        <input placeholder="Name" v-bind="name" />
        <span class="text-danger">{{ errors.name }}</span>
      </div>
      <div class="field">
        <input placeholder="email" v-bind="email">
        <span class="text-danger">{{ errors.email }}</span>
      </div>
      <div class="field">
        <input placeholder="password" v-bind="password">
        <span class="text-danger">{{ errors.password }}</span>
      </div>
      <button type="submit">submit</button>
    </form>
  </div>
</template>
<script>
import { useVeeValidateStore } from '~/stores/vee-validate';
import { storeToRefs } from 'pinia';

export default {
  setup() {
    // Destructuring state and getters
    const { errors, name, email, password } = storeToRefs(useVeeValidateStore());

    // Destructuring action
    const { onSubmit } = useVeeValidateStore();

    return {
      errors,
      name,
      email,
      password,
      onSubmit,
    }
  }
}
</script>
<style lang="">
  
</style>