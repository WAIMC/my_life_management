import { defineStore } from 'pinia'
import { piniaTask } from "types/demo";

export const usePiniaTaskStore = defineStore('piniaTask', {
  state: () => {
    return {
      task: [
        {id: 1, name: 'abc', age: 10, isDied: false},
        {id: 2, name: 'xyz', age: 11, isDied: false},
        {id: 3, name: 'lkj', age: 12, isDied: false},
      ],
      id: 0 as number,
      name: '' as string,
      age: 0 as number,
      isDied: false as boolean,
      isUpdate: false as boolean,
    }
  },
  actions: {
    setName (name:string) {
      this.name = name;
    },
    setAge (age:number) {
      this.age = age;
    },
    setIsDied (isDied:boolean) {
      this.isDied = isDied;
    },
    addNewTask (payload:piniaTask) {
      const {name, age, isDied} = payload;
      this.task = [
        ...this.task,
        {
          id: this.task.reduce((accumulator, current) => { return accumulator.id > current.id ? accumulator : current}),
          name: name.value,
          age: age.value,
          isDied: isDied.value,
        }
      ];
    },
    deleteTask (id: number) {
      this.task = this.task.filter((val) => val.id !== id);
    },
    editTask (id: number) {
      let taskEdit = this.task.filter((val) => val.id === id);
      this.name = taskEdit[0]?.name;
      this.age = taskEdit[0]?.age;
      this.isDied = taskEdit[0]?.isDied;
      this.id = id;
      this.isUpdate = true;
    },
    updateTask () {
      this.task = this.task.map((val) => {
        if (val.id === this.id) {
          val.name = this.name;
          val.age = this.age;
          val.isDied = this.isDied;
        }

        return val;
      });
      console.log('this task :', this.task);
      
      this.isUpdate = false;
    },
    resetForm () {
      this.name = '';
      this.age = 0;
      this.isDied = false;
    }
  },
});