<template lang="">
  <div>
    <div class="v-row v-justify-content-between v-align-items-center v-mt-2">
      <div class="v-show__entries">
        <div class="v-row v-justify-content-between v-align-items-center">
          <div class="v-mr-1">
            <span>Show</span>
          </div>
          <div class="v-mr-1">
            <select name="" id="" class="v-form__input" v-model="showEntries">
              <option 
                v-for="itemEntries in listEntries"
                :key="itemEntries.value"
                :value="itemEntries.value"
                :selected="showEntries === itemEntries.value"
              >
                {{ itemEntries.label }}
              </option>
            </select>
          </div>
          <div class="">
            <span>
              entries
            </span>
          </div>
        </div>
      </div>
      <div class="v-search__entries">
        <input
          type="text"
          class="v-form__input v-input__blue-purple"
          name=""
          id=""
          v-model="findEntries"
          placeholder="Search...">
      </div>
    </div>
    <div class="v-row">
      <table class="v-table v-table__full-option">
        <thead>
          <tr>
            <th
              v-for="(itemHead, indx) of dataHead"
              @click="handleSortByOrders(itemHead.key)"
              :key="indx"
              class="v-head__table"
              :class="(sortKey === itemHead.key ? 'v-active__head-table' : '')"
            >
            <div class="v-row v-justify-content-center v-align-items-center">
              <div class="v-title__head-table v-mr-1">
                {{ itemHead.label }}
              </div>
              <div class="v-sort__head-table">
                <div class="v-row v-flex-direction-column">
                  <i 
                    v-if="(sortKey !== itemHead.key || (sortKey === itemHead.key && orderBy === -1))"
                    class="fa-solid fa-caret-up"
                  ></i>
                  <i 
                    v-if="(sortKey !== itemHead.key || (sortKey === itemHead.key && orderBy === 1))"
                    class="fa-solid fa-caret-down"
                  ></i>
                </div>
              </div> 
            </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(itemBody, indx2) in dataBody"  
            :key="indx2"
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
    <div class="v-row v-justify-content-around v-align-items-center">
      <div class="v-col-6 v-col-lg-6 v-col-md-6 v-sm-12 v-mt-1">
        Show {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} entries
      </div>
      <div class="v-col-6 v-col-lg-6 v-col-md-6 v-sm-12 v-mt-1">
        <ul 
          class="v-pagination v-row v-justify-content-end v-align-items-center"
          v-if="pagination.total > pagination.perPage"
        >
          <li class="v-pagination__item">
            <a href="#" class="v-page__pagination-link" @click.prevent="handleFirstPage">
              <span class="v-nav__pagination">
                First
              </span>
            </a>
          </li>
          <li class="v-pagination__item">
            <a href="#" class="v-page__pagination-link" @click.prevent="handlePrevPage">
              <span class="v-nav__pagination">
                <i class="fa-solid fa-caret-left"></i>
              </span>
            </a>
          </li>
          <li 
            class="v-pagination__item" 
            v-for="(page, indP) in pagination.pages"
            :key="indP"
          >
            <a 
              href="#" 
              class="v-page__pagination-link" 
              @click.prevent="handleChangePage(page)"
              :class="pagination.currentPage === page ? 'v-pagination__active' : ''"
            >
              <span class="v-nav__pagination">
                {{ page }}
              </span>
            </a>
          </li>
          <li class="v-pagination__item">
            <a href="#" class="v-page__pagination-link" @click.prevent="handleNexPage">
              <span class="v-nav__pagination">
                <i class="fa-solid fa-caret-right"></i>
              </span>
            </a>
          </li>
          <li class="v-pagination__item">
            <a href="#" class="v-page__pagination-link" @click.prevent="handleLastPage">
              <span class="v-nav__pagination">
                Last
              </span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
import { ref, watchEffect, watch, PropType } from "vue";
import { sortOrders, searchEntries } from '~/utils/options-entries-utils';
import { OrderByTypes } from "~/types/sort-types";
import * as Constant from "~/constants";
import { HeaderTable } from '~/types/component-types';

export default {
  props: {
    dataHead: Array as PropType<HeaderTable>,
  },
  setup() {
    // Data header table
    const dataHead = ref([
      { key: 'item1', label: 'hehe' },
      { key: 'item2', label: 'haha' },
      { key: 'item3', label: 'hoho' },
      { key: 'item4', label: 'huhu' },
      { key: 'item5', label: 'hihi' },
    ]);

    // Data table
    const data = ref([
      {
        item1: '11111',
        item2: '11111',
        item3: '11111',
        item4: '11111',
        item5: '11111',
      },
      {
        item1: '22222',
        item2: '22222',
        item3: '22222',
        item4: '22222',
        item5: '22222',
      },
      {
        item1: '33333',
        item2: '33333',
        item3: '33333',
        item4: '33333',
        item5: '33333',
      },
      {
        item1: '44444',
        item2: '44444',
        item3: '44444',
        item4: '44444',
        item5: '44444',
      },
      {
        item1: '55555',
        item2: '55555',
        item3: '55555',
        item4: '55555',
        item5: '55555',
      },
      {
        item1: '66666',
        item2: '66666',
        item3: '66666',
        item4: '66666',
        item5: '66666',
      },
      {
        item1: '77777',
        item2: '77777',
        item3: '77777',
        item4: '77777',
        item5: '77777',
      },
      {
        item1: '88888',
        item2: '88888',
        item3: '88888',
        item4: '88888',
        item5: '88888',
      },
      {
        item1: '99999',
        item2: '99999',
        item3: '99999',
        item4: '99999',
        item5: '99999',
      },
      {
        item1: '10101',
        item2: '10101',
        item3: '10101',
        item4: '10101',
        item5: '10101',
      },
      {
        item1: '11a11',
        item2: '11a11',
        item3: '11a11',
        item4: '11a11',
        item5: '11a11',
      },
      {
        item1: '12a12',
        item2: '12a12',
        item3: '12a12',
        item4: '12a12',
        item5: '12a12',
      },
      {
        item1: '13a13',
        item2: '13a13',
        item3: '13a13',
        item4: '13a13',
        item5: '13a13',
      },
      {
        item1: '14a14',
        item2: '14a14',
        item3: '14a14',
        item4: '14a14',
        item5: '14a14',
      },
      {
        item1: '15a15',
        item2: '15a15',
        item3: '15a15',
        item4: '15a15',
        item5: '15a15',
      },
      {
        item1: '16a16',
        item2: '16a16',
        item3: '16a16',
        item4: '16a16',
        item5: '16a16',
      },
      {
        item1: '17a17',
        item2: '17a17',
        item3: '17a17',
        item4: '17a17',
        item5: '17a17',
      },
      {
        item1: '18a18',
        item2: '18a18',
        item3: '18a18',
        item4: '18a18',
        item5: '18a18',
      },
      {
        item1: '19a19',
        item2: '19a19',
        item3: '19a19',
        item4: '19a19',
        item5: '19a19',
      },
      {
        item1: '20a20',
        item2: '20a20',
        item3: '20a20',
        item4: '20a20',
        item5: '20a20',
      },
      {
        item1: '21a21',
        item2: '21a21',
        item3: '21a21',
        item4: '21a21',
        item5: '21a21',
      },
      {
        item1: '22a22',
        item2: '22a22',
        item3: '22a22',
        item4: '22a22',
        item5: '22a22',
      },
      {
        item1: '23a23',
        item2: '23a23',
        item3: '23a23',
        item4: '23a23',
        item5: '23a23',
      },
      {
        item1: '24a24',
        item2: '24a24',
        item3: '24a24',
        item4: '24a24',
        item5: '24a24',
      },
      {
        item1: '25a25',
        item2: '25a25',
        item3: '25a25',
        item4: '25a25',
        item5: '25a25',
      },
      {
        item1: '26a26',
        item2: '26a26',
        item3: '26a26',
        item4: '26a26',
        item5: '26a26',
      },
      {
        item1: '27a27',
        item2: '27a27',
        item3: '27a27',
        item4: '27a27',
        item5: '27a27',
      },
      {
        item1: '28a28',
        item2: '28a28',
        item3: '28a28',
        item4: '28a28',
        item5: '28a28',
      },
      {
        item1: '29a29',
        item2: '29a29',
        item3: '29a29',
        item4: '29a29',
        item5: '29a29',
      },
      {
        item1: '30a30',
        item2: '30a30',
        item3: '30a30',
        item4: '30a30',
        item5: '30a30',
      },
    ]);

    // Data body table
    const dataBody = ref(data.value);

    /**========================= Sort column ========================= */

    // Current key sorting all
    const sortKey = ref('');

    // Type sort for key
    const orderBy = ref(OrderByTypes.ASC);

    // Handle sort by order by asc|desc
    function handleSortByOrders(key) {
      if (sortKey.value === key) { // Sort key multiple time
        /* Toggle type sort asc|desc */
        if (orderBy.value === OrderByTypes.ASC) {
          sortOrders(dataBody.value, OrderByTypes.DESC, key);
          orderBy.value = OrderByTypes.DESC;
        } else {
          sortOrders(dataBody.value, OrderByTypes.ASC, key);
          orderBy.value = OrderByTypes.ASC;
        }
      } else { // Sort key first time
        sortOrders(dataBody.value, OrderByTypes.ASC, key);
      }
      sortKey.value = key;
    }

    /**========================= Filter entries ========================= */

    // Query search all entries
    const findEntries = ref('');

    // Handle search entries when get changing
    watch(findEntries, (currentVal) => {
      dataBody.value = searchEntries(data.value, currentVal);
    })

    /**========================= Show number entries ========================= */

    // List option choose number entries show
    const listEntries = ref(Constant.LIST_ENTRIES);

    // Current number entries showed
    const showEntries = ref(Constant.DEFAULT_SHOW_NUMBER_ENTRIES);

    /**========================= Pagination entries ========================= */

    // Option display table
    const pagination = ref({
      total: 100, // Total record
      perPage: 0, // Number record in a page
      currentPage: 1, // Current page
      firstPage: 1, // First page
      lastPage: 0, // Last page
      from: 1, // From number item
      to: 1, // To number item
      pages: [], // Pages
    });

    // Handle calculator entries showing | same useEffect + dependence of reactjs
    watchEffect((currentVal) => {
      let currentEntries = [...data.value];

      // Calculator total entries
      pagination.value.total = data.value.length;

      // Calculator number entries per page
      pagination.value.perPage = showEntries.value;

      // Calculator last page
      if (pagination.value.total > pagination.value.perPage) {
        pagination.value.lastPage = Math.ceil(pagination.value.total / pagination.value.perPage);
      }

      // Calculator position entries begin in current page
      pagination.value.from = pagination.value.currentPage * pagination.value.perPage - (pagination.value.perPage - 1);

      // Calculator position entries end in current page
      if (
        pagination.value.currentPage < pagination.value.lastPage
        || pagination.value.total % pagination.value.perPage === 0
      ) {
        pagination.value.to = pagination.value.currentPage * pagination.value.perPage;
      } else {
        pagination.value.to = pagination.value.from - 1 + pagination.value.total % pagination.value.perPage;
      }

      // Calculator pagination page
      if (pagination.value.pages.length !== pagination.value.lastPage) {
        pagination.value.pages = [];
      }

      if (pagination.value.lastPage > Constant.NUMBER_PAGE_PAGINATION) {
        if (pagination.value.currentPage === pagination.value.lastPage) {
          pagination.value.pages = [
            pagination.value.currentPage - 2,
            pagination.value.currentPage - 1,
            pagination.value.currentPage
          ];
        } else if (pagination.value.currentPage === pagination.value.firstPage) {
          pagination.value.pages = [
            pagination.value.currentPage,
            pagination.value.currentPage + 1,
            pagination.value.currentPage + 2
          ];
        } else {
          pagination.value.pages = [
            pagination.value.currentPage - 1,
            pagination.value.currentPage,
            pagination.value.currentPage + 1,
          ];
        }
      } else {
        for (let page = 0; page < pagination.value.lastPage; page++) {
          if (pagination.value.pages.length === pagination.value.lastPage) {
            break;
          }
          pagination.value.pages[page] = page + 1;
        }
      }

      // Calculator entries of page
      currentEntries = currentEntries.splice(
        pagination.value.from - 1,
        (pagination.value.to - pagination.value.from + 1)
      );

      // Set value for data body table
      dataBody.value = [...currentEntries];
    });

    // Handle redirect to first page
    function handleFirstPage() {
      pagination.value.currentPage = pagination.value.firstPage;
    }

    // Handle redirect to prev page
    function handlePrevPage() {
      if (pagination.value.currentPage > pagination.value.firstPage) {
        handleChangePage(pagination.value.currentPage - 1);
      } else {
        handleChangePage(pagination.value.lastPage);
      }
    }

    // Handle redirect to nex page
    function handleNexPage() {
      if (pagination.value.currentPage < pagination.value.lastPage) {
        handleChangePage(pagination.value.currentPage + 1);
      } else {
        handleChangePage(pagination.value.firstPage);
      }
    }

    // Handle redirect to last page
    function handleLastPage() {
      pagination.value.currentPage = pagination.value.lastPage;
    }

    // Handle set current page
    function handleChangePage(page) {
      pagination.value.currentPage = page;
    }

    return {
      dataHead,
      dataBody,
      pagination,
      sortKey,
      orderBy,
      findEntries,
      listEntries,
      showEntries,
      handleSortByOrders,
      handleChangePage,
      handlePrevPage,
      handleNexPage,
      handleFirstPage,
      handleLastPage,
    };
  }
}
</script>
<style lang="">
  
</style>