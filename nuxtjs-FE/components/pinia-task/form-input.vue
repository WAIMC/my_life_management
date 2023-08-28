<template>
  <div>
    <div class="row d-flex align-items-center">
      <div class="col-3">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" name="name" id="name" aria-describedby="helpId" placeholder="Input name"
            v-model="nameTask">
          <small id="helpId" class="form-text text-muted"></small>
        </div>
      </div>
      <div class="col-3">
        <div class="form-group">
          <label for="age">Age</label>
          <input type="text" class="form-control" name="age" id="age" aria-describedby="helpId" placeholder="age"
            v-model="ageTask">
          <small id="helpId" class="form-text text-muted"></small>
        </div>
      </div>
      <div class="col-3">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="die" v-model="isDiedTask">
          <label class="form-check-label" for="die">Die</label>
        </div>
      </div>
      <div class="col-3">
        <button type="button" class="btn btn-primary" :class="isUpdate ? 'btn-success' : 'btn-info'"
          @click="isUpdate ? updateItem() : addNew()">
          {{ isUpdate ? 'Update' : 'Add New' }}
        </button>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { usePiniaTaskStore } from "@/stores/pinia-task";
import { ref, computed, watch } from 'vue'
import { storeToRefs } from "pinia";
import { log } from 'console';

export default {
  setup() {
    // Destructuring for state
    const { name, age, isDied, task, isUpdate } = storeToRefs(usePiniaTaskStore());

    // Destructuring for function
    const {
      setName,
      setAge,
      setIsDied,
      addNewTask,
      deleteTask,
      resetForm,
      updateTask,
    } = usePiniaTaskStore();

    /**
    * Two way binding state by get/set
    */
    const nameTask = computed({
      get: () => name.value,
      set: (val) => setName(val),
    });

    const ageTask = computed({
      get: () => age.value,
      set: (val) => setAge(val),
    });

    const isDiedTask = computed({
      get: () => isDied.value,
      set: (val) => setIsDied(!!val),
    });

    // Add new task
    function addNew() {
      addNewTask({ name, age, isDied });
      resetForm();
    };

    // Update task
    function updateItem() {
      updateTask();
      resetForm();
    };

    return { nameTask, ageTask, isDiedTask, addNew, isUpdate, updateItem };
  }
}
</script>
<style lang="">
  
</style>
