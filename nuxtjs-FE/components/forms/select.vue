<template lang="">
  <div>
    <div class="mb-3">
      <label :for="'labelSelect' + uuid" class="form-label">
        {{ label }}
      </label>
      <Field
        as="select"
        class="form-select"
        :id="'labelSelect' + uuid"
        :name="name || ''"
        v-model="stateValue"
        :rules="rules || ''"
        v-slot="{ value }"
      >
        <option selected>PLS choose a option</option>
        <option
          v-for="(list, indx) of lists"
          :key="indx"
          :value="list?.value"
          :selected="value && value.includes(list?.value)"
        >
          {{ list?.label }}
        </option>
      </Field>
      <ErrorMessage class="text-danger" :name="name || ''" />
    </div>
  </div>
</template>
<script lang="ts">
import _ from 'lodash';
import { computed, PropType } from 'vue';
import { Field, ErrorMessage } from 'vee-validate';
import { FormSelect } from "~/types/form-types";

export default {
  props: {
    stateVal: String || Number,
    label: String,
    name: String,
    rules: String,
    lists: Object as PropType<FormSelect>,
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
