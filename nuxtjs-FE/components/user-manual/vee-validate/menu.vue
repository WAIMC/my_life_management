<template lang="">
  <div class="d-flex justify-content-between align-content-center">
    <div class="">
      <button 
        class="btn btn-primary" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#validateId" 
        aria-expanded="false"
        aria-controls="validateId"
        @click="changeTypeCollapse(0)"
      >
          {{ $t('UserManual.VeeValidate.Label.TUTORIAL.VALIDATE_BASIC') }}
      </button>
    </div>
    <div class="">
      <button 
        class="btn btn-primary" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#validateId" 
        aria-expanded="false"
        aria-controls="validateId"
        @click="changeTypeCollapse(1)"
      >
          {{ $t('UserManual.VeeValidate.Label.TUTORIAL.FROM_GENERATOR') }}
      </button>
    </div>
    <div class="">
      <button 
        class="btn btn-primary" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#validateId" 
        aria-expanded="false"
        aria-controls="validateId"
        @click="changeTypeCollapse(3)"
      >
          {{ $t('UserManual.VeeValidate.Label.TUTORIAL.COMPLETE_VALIDATE') }}
      </button>
    </div>
  </div>
  <div class="collapse" id="validateId">
    <div class="p-2" v-if="typeCollapse === 0">
      <Basic />
    </div>
    <div class="p-2" v-if="typeCollapse === 1">
      <FormGenerator />
    </div>
    <div class="p-2" v-if="typeCollapse === 3">
      <CompleteValidate />
    </div>
  </div>
</template>
<script>
import { ref } from 'vue';
import Basic from '~/components/user-manual/vee-validate/basic.vue';
import FormGenerator from '~/components/user-manual/vee-validate/form-generator.vue';
import CompleteValidate from '~/components/user-manual/vee-validate/complete-validate.vue';

export default {
  components: {
    Basic,
    FormGenerator,
    CompleteValidate,
  },
  setup() {
    const typeCollapse = ref(0);

    function changeTypeCollapse (type) {
      typeCollapse.value = type;
    }

    return { typeCollapse, changeTypeCollapse };
  }
}
</script>
<style lang="">
  
</style>