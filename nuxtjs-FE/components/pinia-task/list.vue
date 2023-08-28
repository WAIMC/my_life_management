<template>
  <div>
    <table class="table table-bordered table-active">
      <thead>
        <tr class="text-center">
          <th>Name</th>
          <th>Age</th>
          <th>Died</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(el, indx) in task" :key="indx" class="text-center">
          <td>{{ el.name }}</td>
          <td>{{ el.age }}</td>
          <td>{{ el.isDied }}</td>
          <td class="text-danger">
            <button type="button" class="btn btn-danger" @click="delTask(el.id)">
              Remove
            </button>
            <button type="button" class="btn btn-info ml-2" @click="editEl(el.id)">
              Edit
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
import { ref, computed } from 'vue';
import { storeToRefs } from "pinia";
import { usePiniaTaskStore } from "@/stores/pinia-task";

export default {
  setup() {
    // Destructuring for state
    const { task } = storeToRefs(usePiniaTaskStore());

    // Destructuring for function
    const { deleteTask, editTask } = usePiniaTaskStore();

    const delTask = (id) => {
      deleteTask(id);
    }

    const editEl = (id) => {
      editTask(id);
    }

    return { task, delTask, editEl };
  }
}
</script>
<style lang="ts">

</style>
