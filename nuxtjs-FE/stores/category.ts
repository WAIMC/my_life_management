import { defineStore } from 'pinia';

export const useCategoryStore = defineStore('category', {
  state: () => {
    return {
      id: 0,
      parent_id: 0,
      name: '',
      slug: '',
      description: '',
      status: 0,
      isDisplay: false,
      rankOrder: 0,
      createdAt: '',
      categoryList: []
    }
  },
  actions: {
    setCategoryParentId (id:number) {
      this.id = id;
    },
    setCategoryName (name:string) {
      this.name = name;
    },
    setCategorySlug (slug:string) {
      this.slug = slug;
    },
    setCategoryDescription (description:string) {
      this.description = description;
    },
    setCategoryStatus (status:number) {
      this.status = status;
    },
    setCategoryIsDisplay (isDisplay:boolean) {
      this.isDisplay = isDisplay;
    },
    setCategoryRankOrder (rankOrder:number) {
      this.rankOrder = rankOrder;
    },
    setCategoryCreatedAt (createdAt:string) {
      this.createdAt = createdAt;
    },
    getAllCategory () {
      
    }
  },
});