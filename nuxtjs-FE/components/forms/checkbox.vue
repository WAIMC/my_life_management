<template lang="">
  <div>
    <div class="form-radio">
      <div 
        class="form-check"
        v-for="(list, indx) of lists"
        :key="indx"
      >
        <Field
          type="checkbox"
          class="form-check-input"
          :id="'labelRadio' + uuid + indx"
          :name="name || ''"
          v-model="stateValue"
          :rules="rules || ''"
          :value="list?.value"
          :checked="list?.value === stateValue"
        />
        <label :for="'labelRadio' + uuid + indx" class="form-check-label">
          {{ list.label }}
        </label>
      </div>
    </div>
    <ErrorMessage class="text-danger" :name="name || ''" />
  </div>
</template>
<script lang="ts">
import _ from 'lodash';
import { computed, PropType } from 'vue';
import { Field, ErrorMessage } from 'vee-validate';
import { FormRadio } from "~/types/form-types";

export default {
  props: {
    stateVal: Object as PropType<Number>,
    name: String,
    rules: String,
    lists: Object as PropType<FormRadio>,
  },
  emits: [
    'onSetState',
  ],
  components: {
    Field,
    ErrorMessage,
  },
  setup(props, ctx) {
    // Create unique index
    const uuid = _.uniqueId();

    // Two-way binding state by get/set
    const stateValue = computed({
      get: () => props.stateVal,
      set: (val) => {
        console.log('val :', val);
        
        return ctx.emit('onSetState', val)
      },
    });

    return { stateValue, uuid };
  }
}
</script>
<style lang="">
  
</style>
