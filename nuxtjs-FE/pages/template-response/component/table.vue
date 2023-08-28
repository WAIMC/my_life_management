<template lang="">
  <div>
    <Breadcrumb :titleBreadcrumb="'Table basic....aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'"/>

    <div class="v-row v-justify-content-between v-align-items-baseline">
      <div class="v-col-6 v-col-lg-6 v-col-md-6 v-col-sm-12">
        <div class="v-bg-dark-gray v-rounded-last v-box__shadow v-m-1 v-p-2">
          <div>
            <h3 class="v-m-1 v-color-yellow">Table Border Bottom</h3>
            <div class="v-row">
              <table class="v-table v-table__border-bottom">
                <thead>
                  <tr>
                    <th
                      v-for="(itemHead, indxH1) in dataHeadTable"
                      :key="indxH1"
                    >
                      {{ itemHead }} 
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(itemBody, indxB1) in dataBodyTable"  
                    :key="indxB1"
                  >
                    <td> {{ itemBody.item1 }} </td>
                    <td> {{ itemBody.item2 }} </td>
                    <td> {{ itemBody.item3 }} </td>
                    <td> {{ itemBody.item4 }} </td>
                    <td> {{ itemBody.item5 }} </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="v-col-6 v-col-lg-6 v-col-md-6 v-col-sm-12 v-mt-3">
        <div class="v-bg-dark-gray v-rounded-last v-box__shadow v-m-1 v-p-2">
          <div>
            <h3 class="v-m-1 v-color-yellow">Table Border Full</h3>
            <table class="v-table v-table__border-full">
              <thead>
                <tr>
                  <th 
                    v-for="(itemHead3, indxH3) of dataHeadTable"
                    :key="indxH3"
                  > 
                    {{ itemHead3 }} 
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(itemBody3, indxB3) in dataBodyTable"  
                  :key="indxB3"
                >
                  <td> {{ itemBody3.item1 }} </td>
                  <td> {{ itemBody3.item2 }} </td>
                  <td> {{ itemBody3.item3 }} </td>
                  <td> {{ itemBody3.item4 }} </td>
                  <td> {{ itemBody3.item5 }} </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="v-row v-justify-content-between v-align-items-baseline">
      <div class="v-col-12 v-col-lg-12 v-col-md-12 v-col-sm-12 v-mt-2">
        <div class="v-bg-dark-gray v-rounded-last v-box__shadow v-m-1 v-p-2">
          <div class="v-mt-1">
            <h3 class="v-m-1 v-color-yellow">Table Full Option</h3>
            <TableBorderBottom />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { ref } from 'vue';
import Breadcrumb from '~/components/breadcrumb.vue';
import TableBorderBottom from '~/components/template/table/table-border-bottom.vue';

export default {
  components: {
    Breadcrumb,
    TableBorderBottom,
  },
  setup() {
    const dataHeadTable = ref([
      'tHead1',
      'tHead2',
      'tHead3',
      'tHead4',
      'tHead5',
    ]);

    const dataBodyTable = ref([
      {
        item1: 'body1',
        item2: 'body2',
        item3: 'body3',
        item4: 'body4',
        item5: 'body5',
      },
      {
        item1: 'body1',
        item2: 'body2',
        item3: 'body3',
        item4: 'body4',
        item5: 'body5',
      },
      {
        item1: 'body1',
        item2: 'body2',
        item3: 'body3',
        item4: 'body4',
        item5: 'body5',
      },
      {
        item1: 'body1',
        item2: 'body2',
        item3: 'body3',
        item4: 'body4',
        item5: 'body5',
      },
      {
        item1: 'body1',
        item2: 'body2',
        item3: 'body3',
        item4: 'body4',
        item5: 'body5',
      },
    ]);

    return {
      dataHeadTable,
      dataBodyTable,
    }
  }
}
</script>
<style lang="">
  
</style>