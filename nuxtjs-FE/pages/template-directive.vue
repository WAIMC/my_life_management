<template>
  <div>

    <!-- Header start -->
    <menuHeader />
    <!-- Header end -->

    <!-- Main start -->
    <div class="main mt-5">
      <h1>Template Directive</h1>

      <div class="row">
        <div class="col-3 border rounded">
          Name : {{ name }}
        </div>
        <div class="col-3 border rounded">
          <div class="row">
            <div class="col-6 border rounded" id="vif" v-if="isActive">
              <span>Check v-if</span>
            </div>
            <div class="col-6 border rounded" id="vshow" v-show="isActive">
              <span>Check v-model</span>
            </div>
          </div>
        </div>
        <div class="col-3 border rounded">
          <span>Use v-for :</span>
          <ul>
            <li v-for="(subject, indx) in subjects" :key="indx">{{ subject }}</li>
          </ul>
        </div>
        <div class="col-3 border rounded">
          <div class="form-group">
            <label for="message">Check v-model</label>
            <input type="text" class="form-control" name="message" id="message" aria-describedby="helpId"
              placeholder="Input here..." v-model="message">
            <small id="helpId" class="form-text text-danger">{{ message }}</small>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-3 border rounded">
          <button type="button" class="btn btn-primary" @click="increment">
            Increment
          </button>
          <p>{{ count }}</p>
        </div>
        <div class="col-3 border rounded">
          <h5>Get full name (method) first : {{ fullNameMe() }}</h5>
          <h5>Get full name (computed) first : {{ fullNameCom }}</h5>
          <h5>Get full name (method) second : {{ fullNameMe() }}</h5>
          <h5>Get full name (method) second : {{ fullNameCom }}</h5>
        </div>
        <div class="col-3 border rounded"></div>
        <div class="col-3 border rounded"></div>
      </div>
    </div>
    <!-- Main end -->

  </div>
</template>
<script>
export default {
  data() {
    return {
      name: 'abc',
      address: '123, xxyz',
      subjects: [
        'aaa', 'bbb', 'ccc'
      ],
      isActive: false,
      message: '',
      count: 0,
      firstName: 'abc',
      lastName: 'xyz',
    }
  },
  methods: {
    increment: function () {
      console.log('run increment() method');
      return this.count++;
    },
    fullNameMe: function () {
      console.log('Run function method');
      return this.lastName + ' ' + this.firstName;
    }
  },
  computed: {
    fullNameCom: function () {
      console.log('run function computed');
      return this.lastName + ' ' + this.firstName;
    },
  },
  watch: {
    count: function () {
      console.log('run watch');
      alert(this.count);
    }
  },
  beforeCreate() {
    console.log('Before created------------------------------------');
    console.log(this.count);
  },
  created() {
    console.log('Created ------------------------------------------');
    console.log(this.count);
    console.log(this.fullNameMe);
    console.log(this.fullNameCom);
  },
  beforeMount() {
    console.log('Before mount ----------------------------------');
  },
  beforeUpdate() {
    console.log('Before update---------------------------------');
  },
  updated() {
    console.log('Updated----------------------------------------');
  },
}
</script>
<style></style>
