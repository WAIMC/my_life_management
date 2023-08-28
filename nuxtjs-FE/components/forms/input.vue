<template lang="">
  <div>
    <div class="mb-3">
      <label :for="'labelText' + uuid" class="form-label">
        {{ label }}
      </label>
      <Field
        :type="type || 'text'"
        class="form-control"
        :id="'labelText' + uuid"
        :placeholder="placeholder || ''"
        :name="name || ''"
        v-model="stateValue"
        :rules="rules || ''"
        autocomplete="off"
      />
      <ErrorMessage class="text-danger" :name="name || ''" />
    </div>
  </div>
</template>
<script>
import _ from 'lodash';
import { computed } from 'vue';
import { Field, ErrorMessage } from 'vee-validate';

export default {
  props: {
    stateVal: String | Number | Boolean,
    type: String,
    label: String,
    placeholder: String,
    name: String,
    rules: String,
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
        return ctx.emit('onSetState', val)
      },
    });

    return { stateValue, uuid };
  }
}
</script>
<style lang="">
  
</style>
