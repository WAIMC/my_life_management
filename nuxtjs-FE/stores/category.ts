import { defineStore } from "pinia";
import * as Utils from "~/utils";
import * as CategoryType from "types/category-types";
import * as UrlApi from "~/constants/api";
import _ from "lodash";

export const useCategoryStore = defineStore("category", {
  state: () => {
    return {
      id: 0,
      parentId: 0,
      name: "",
      slug: "",
      description: "",
      status: 0,
      isDisplay: false,
      rankOrder: 0,
      createdAt: "",
      updatedAt: "",
      isEdit: false,
      categoryList: [] as CategoryType.CategoryList[],
    };
  },
  actions: {
    /**
     * Set value for state id
     * @param number id
     */
    setCategoryId(id: number) {
      this.id = id;
    },

    /**
     * Set value for state parentId
     * @param number id
     */
    setCategoryParentId(id: number) {
      this.parentId = id;
    },

    /**
     * Set value for state name
     * @param string name
     */
    setCategoryName(name: string) {
      this.name = name;
    },

    /**
     * Set value for state slug
     * @param string slug
     */
    setCategorySlug(slug: string) {
      this.slug = slug;
    },

    /**
     * Set value for state description
     * @param string description
     */
    setCategoryDescription(description: string) {
      this.description = description;
    },

    /**
     * Set value for state status
     * @param number status
     */
    setCategoryStatus(status: number) {
      this.status = status;
    },

    /**
     * Set value for state isDisplay
     * @param boolean isDisplay
     */
    setCategoryIsDisplay(isDisplay: boolean) {
      this.isDisplay = isDisplay;
    },

    /**
     * Set value for state rankOrder
     * @param number rankOrder
     */
    setCategoryRankOrder(rankOrder: number) {
      this.rankOrder = rankOrder;
    },

    /**
     * Set value for state createdAt
     * @param string createdAt
     */
    setCategoryUpdatedAt(createdAt: string) {
      this.createdAt = createdAt;
    },

    /**
     * Set value for state updatedAt
     * @param string updatedAt
     */
    setCategoryCreatedAt(updatedAt: string) {
      this.updatedAt = updatedAt;
    },

    /**
     * Set flag check is edit
     * @param boolean isEdit
     */
    setIsEdit(isEdit: boolean) {
      this.isEdit = isEdit;
    },

    /**
     * Action get all category
     */
    async getAllCategory() {
      const categoryList = await Utils.API.actionGet<
        CategoryType.CategoryList[]
      >(UrlApi.GET_ALL_CATEGORY);
      this.categoryList = categoryList ?? [];
    },

    /**
     * Action create new category
     */
    async insertCategory() {
      const result = await Utils.API.actionPost<CategoryType.CategoryList[]>(
        UrlApi.CREATE_CATEGORY,
        this.newCategory
      );

      if (result) {
        this.resetData();
        this.getAllCategory();
      }
    },

    /**
     * Action edit category
     * @param number id
     */
    editCategory(id: number) {
      this.setIsEdit(true);
      this.setDataEdit(id);
    },

    /**
     * Reset data to default
     */
    resetData() {
      this.setCategoryId(0);
      this.setCategoryParentId(0);
      this.setCategoryName("");
      this.setCategorySlug("");
      this.setCategoryDescription("");
      this.setCategoryStatus(0);
      this.setCategoryIsDisplay(false);
      this.setCategoryRankOrder(0);
      this.setCategoryCreatedAt("");
      this.setCategoryUpdatedAt("");
    },

    /**
     * Set data for edit category
     * @param number id
     */
    setDataEdit(id: number) {
      const category = this.categoryList.find((val) => val.id === id);
      if (category) {
        this.setCategoryId(+category.id);
        this.setCategoryParentId(category.parent_id);
        this.setCategoryName(category.name);
        this.setCategorySlug(category.slug);
        this.setCategoryDescription(category.description);
        this.setCategoryStatus(category.status);
        this.setCategoryIsDisplay(category.is_display);
        this.setCategoryRankOrder(category.rank_order);
        this.setCategoryCreatedAt(category.created_at);
        this.setCategoryUpdatedAt(category.updated_at);
      }
    },

    /**
     * Action update category
     */
    async updateCategory() {
      const result = await Utils.API.actionPut(
        UrlApi.UPDATE_CATEGORY.replace(":id", "" + this.id),
        this.updateRecordCategory
      );

      if (result) {
        this.resetData();
        this.setIsEdit(false);
        this.getAllCategory();
      }
    },

    /**
     * Action delete category
     * @param number id
     */
    async deleteCategory(id: number) {
      const result = await Utils.API.actionDelete(
        UrlApi.DELETE_CATEGORY.replace(":id", "" + id),
        {}
      );

      if (result) {
        this.getAllCategory();
      }
    },
  },
  getters: {
    /**
     * Init data for create category
     * @param state
     * @returns object
     */
    newCategory: (state) => {
      return {
        parent_id: state.parentId,
        name: state.name,
        slug: _.kebabCase(state.name),
        description: state.description,
        status: state.status,
        is_display: state.isDisplay,
        rank_order: state.rankOrder,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
      };
    },

    /**
     * Init data for update category
     * @param state
     * @returns object
     */
    updateRecordCategory: (state) => {
      return {
        parent_id: state.parentId,
        name: state.name,
        slug: _.kebabCase(state.name),
        description: state.description,
        status: state.status,
        is_display: state.isDisplay,
        rank_order: state.rankOrder,
        created_at: state.createdAt,
        updated_at: new Date().toISOString(),
      };
    },

    /**
     * Check is editing
     * @param state
     * @returns boolean
     */
    onEdit: (state) => {
      return state.isEdit;
    },
  },
});
