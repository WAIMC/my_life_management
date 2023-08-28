<template lang="">
  <div>
    <table class="table table-striped table-inverse table-responsive text-white border-info">
      <thead class="thead-inverse">
        <tr>
          <th>{{  $t('Category.CATEGORY_NAME') }}</th>
          <th>{{  $t('Category.DESCRIPTION') }}</th>
          <th>{{  $t('Category.STATUS') }}</th>
          <th>{{  $t('Category.CREATED_AT') }}</th>
          <th>{{  $t('Common.LabelEnum.ACTION') }}</th>
        </tr>
        </thead>
        <tbody>
          <tr 
            v-for="(category, indx) in categoryList"
            :key="indx"
          >
            <td scope="row">{{ category.name }}</td>
            <td>{{ category.description }}</td>
            <td>
              <span class="badge badge-danger" v-if="category.status === 1"></span>
              <span class="badge badge-primary" v-else></span>
            </td>
            <td>{{ category.created_at }}</td>
            <td>
              <button 
                type="button" 
                class="btn btn-info"
                @click="onEdit(category.id)"
              >
                {{ $t('Common.LabelEnum.EDIT') }}
              </button>
              <button 
                type="button" 
                class="btn btn-danger ml-2"
                @click="onDelete(category.id)"
              >
                {{ $t('Common.LabelEnum.DELETE') }}
              </button>
            </td>
          </tr>
        </tbody>
    </table>
  </div>
</template>
<script>
import { storeToRefs } from "pinia";
import { useCategoryStore } from "~/stores/category";
import { onMounted } from 'vue';

export default {
  setup() {
    /**
     * Destructuring for state and getters
     */
    const {
      categoryList,
    } = storeToRefs(useCategoryStore());

    /**
     * Destructuring for action
     */
    const {
      editCategory,
      getAllCategory,
      deleteCategory,
    } = useCategoryStore();

    /**
     * Handle on edit category
     */
    function onEdit(id) {
      editCategory(id);
    }

    /**
     * Handle on delete category
     */
    function onDelete(id) {
      deleteCategory(id);
    }

    /**
     * Handle when on mounted
     */
    onMounted(() => {
      getAllCategory();
    });

    return {
      categoryList,
      onEdit,
      onDelete,
    };
  }
}
</script>
<style lang="">
  
</style>
