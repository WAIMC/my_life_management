<template lang="">
  <div>
    <h1 class="text-danger">Do not use destructuring with props, because not get value latest</h1>
    <Form>
      <FormInput
        :stateVal="description"
        :type="'email'"
        :label="'Email Address'"
        :placeholder="'fill email'"
        @onSetState="setCategoryDescription"
        :name="'email'"
        :rules="'required|email'"
      />
      <FormSelect
        :stateVal="name"
        :label="'list option'"
        @onSetState="setCategoryName"
        :name="'name'"
        :rules="'required'"
        :lists="listsOption"
      />
      <div>
        <span>form radio</span>
        <FormRadio
          :stateVal="status"
          @onSetState="setCategoryStatus"
          :name="'status'"
          :rules="'required'"
          :lists="listRadio"
        />
      </div>
      <div>
        <span>form checkbox</span>
        <FormCheckbox
          :stateVal="valueCheckbox"
          @onSetState="curdFormCheckbox"
          :name="'testcheckbox'"
          :rules="'required'"
          :lists="listRadio"
        />
      </div>
      <button>submit</button>
    </Form>
  </div>
</template>
<script lang="ts">
import FormInput from "~/components/forms/input.vue";
import FormSelect from "~/components/forms/select.vue";
import FormRadio from "~/components/forms/radio.vue";
import FormCheckbox from "~/components/forms/checkbox.vue";
import { useCategoryStore } from '~/stores/category';
import { storeToRefs } from 'pinia';
import { Form } from 'vee-validate';
import { FormSelect as FormSelectType, FormRadio as FormRadioType } from "~/types/form-types";
import { ref } from 'vue';

export default {
  components: {
    FormInput,
    Form,
    FormSelect,
    FormRadio,
    FormCheckbox,
  },
  setup() {
    // Destructuring state and getters
    const { name, description, status } = storeToRefs(useCategoryStore());

    // Destructuring action
    const { setCategoryName, setCategoryDescription, setCategoryStatus } = useCategoryStore();

    const listsOption: FormSelectType[] = [
      {label: 'name1', value: '1'},
      {label: 'name2', value: '2'},
      {label: 'name3', value: '3'},
      {label: 'name4', value: '4'},
      {label: 'name5', value: '5'},
    ];

    const listRadio: FormRadioType[] = [
      {label: 'check1', value: 1},
      {label: 'check2', value: 2},
      {label: 'check3', value: 3},
      {label: 'check4', value: 4},
      {label: 'check5', value: 5},
    ];

    const valueCheckbox = ref([]);

    function curdFormCheckbox (val: any) {
      valueCheckbox.value = val;
    }
    
    return {
      description,
      name,
      status,
      listsOption,
      listRadio,
      valueCheckbox,
      setCategoryName,
      setCategoryDescription,
      setCategoryStatus,
      curdFormCheckbox,
    }
  }
}
</script>
<style lang="">
  
</style>
