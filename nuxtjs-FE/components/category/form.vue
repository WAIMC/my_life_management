<template lang="">
  <div>
    <div class="row border rounded">
      <div class="col-4 form-group">
        <label for="name">{{ $t('Category.CATEGORY_NAME') }}</label>
        <input 
          type="text" 
          class="form-control" 
          name="name" 
          id="name" 
          aria-describedby="helpId" 
          placeholder="Input nam..."
          v-model="categoryName"
        >
        <small id="helpId" class="form-text text-muted">Input Name</small>
      </div>
      <div class="col-4 form-group">
        <label for="parentId">{{ $t('Category.CATEGORY_PARENT')}}</label>
        <select 
          class="form-control" 
          name="parentId" 
          id="parentId"
          v-model="categoryParentId"
        >
          <option selected>{{ $t('Category.PARENT_ROOT') }}</option>
          <option 
            v-for="(category, indx) of categoryList"
            :key="indx"
            :value="category.id"
            :selected="(categoryParentId === category.id)"
          >
            {{ category.name }}
          </option>
        </select>
      </div>
      <div class="col-4 form-group">
        <label for="rankOrder">{{ $t('Category.RANK_ORDER') }}</label>
        <input 
          type="number"
          class="form-control" 
          name="rankOrder" 
          id="rankOrder" 
          aria-describedby="helpId" 
          placeholder="Order"
          v-model="categoryRankOrder"
        >
        <small id="helpId" class="form-text text-muted">Rank Order</small>
      </div>
    </div>
    <div class="row border rounded">
      <div class="col-6">
        <span>{{ $t('Category.STATUS')}}</span>
        <div>
          <div class="form-check form-check-inline">
            <label class="form-check-label">
              <input 
                class="form-check-input" 
                type="radio" 
                name="status" 
                id="status" 
                value="0" 
                checked
                v-model="categoryStatus"
              > Display value
            </label>
          </div>
          <div class="form-check form-check-inline">
            <label class="form-check-label">
              <input 
                class="form-check-input" 
                type="radio" 
                name="status" 
                id="status" 
                value="1"
                v-model="categoryStatus"
              > Display value
            </label>
          </div>
        </div>
      </div>
      <div class="col-6 form-check">
        <div>
          <span>{{ $t('Category.IS_DISPLAY')}}</span>
          <div>
            <div class="form-check form-check-inline">
              <label class="form-check-label">
                <input 
                  class="form-check-input" 
                  type="radio" 
                  name="isDisplay" 
                  id="isDisplay" 
                  value="0" 
                  checked
                  v-model="categoryIsDisplay"
                > 
                {{ $t('Category.SHOW') }}
              </label>
            </div>
            <div class="form-check form-check-inline">
              <label class="form-check-label">
                <input 
                  class="form-check-input" 
                  type="radio" 
                  name="isDisplay" 
                  id="isDisplay" 
                  value="1"
                  v-model="categoryIsDisplay"
                > 
                {{ $t('Category.HIDE') }}
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row border rounded">
      <div class="form-group p-3">
        <label for="description">{{ $t('Category.DESCRIPTION') }}</label>
        <textarea 
          class="form-control" 
          name="description" 
          id="description" 
          rows="10" 
          cols="100"
          v-model="categoryDescription"
        ></textarea>
      </div>
    </div>
    <div class="row">
      <button 
        type="button" 
        class="btn btn-primary m-2"
        @click="(!onEdit) ? onInsertCategory() : onUpdateCategory()"
      >
        {{ (!onEdit) ? $t('Common.LabelEnum.ADD') : $t('Common.LabelEnum.UPDATE')}}
      </button>
    </div>
  </div>
</template>
<script>
import { storeToRefs } from "pinia";
import { useCategoryStore } from '~/stores/category';
import { computed, onMounted } from 'vue';

export default {
  setup() {
    // Destructuring for state of store
    const {
      parentId,
      name,
      slug,
      description,
      status,
      isDisplay,
      rankOrder,
      categoryList,
      onEdit,
    } = storeToRefs(useCategoryStore());

    // Destructuring for action and getter of store
    const {
      setCategoryParentId,
      setCategoryName,
      setCategoryDescription,
      setCategoryStatus,
      setCategoryIsDisplay,
      setCategoryRankOrder,
      insertCategory,
      updateCategory,
    } = useCategoryStore();

    /**
     * Two-way binding state by get/set
     */
    const categoryParentId = computed({
      get: () => parentId.value,
      set: (val) => setCategoryParentId(val),
    });

    const categoryName = computed({
      get: () => name.value,
      set: (val) => setCategoryName(val),
    });

    const categoryDescription = computed({
      get: () => description.value,
      set: (val) => setCategoryDescription(val),
    });

    const categoryStatus = computed({
      get: () => status.value,
      set: (val) => setCategoryStatus(+val),
    });

    const categoryRankOrder = computed({
      get: () => rankOrder.value,
      set: (val) => setCategoryRankOrder(val),
    });

    const categoryIsDisplay = computed({
      get: () => isDisplay.value & 1,
      set: (val) => setCategoryIsDisplay(!!(+val)),
    });

    /**
     * Handle when on insert category
     */
    function onInsertCategory() {
      insertCategory();
    }

    /**
     * Handle when on update category
     */
    function onUpdateCategory() {
      updateCategory();
    }

    return {
      categoryParentId,
      categoryName,
      categoryDescription,
      categoryStatus,
      categoryRankOrder,
      categoryIsDisplay,
      categoryList,
      onEdit,
      onInsertCategory,
      onUpdateCategory,
    };
  }
}
</script>
<style lang="">
  
</style>
