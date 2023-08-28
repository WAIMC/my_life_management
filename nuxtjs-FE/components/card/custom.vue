<template lang="">
  <div>
    <div class="v-card__custom" :class="(toggleNotification ? 'v-display-block' : 'v-display-none')">
      <div class="v-card__header">
       <h3>NOTIFICATION (10)</h3>
      </div>
      <ul class="v-card__body">
        <li>
          <a href="#">
            <div class="v-ntf__items v-row v-justify-content-start v-align-items-start">
              <div class="v-ntf__item">
                <img src="~/assets/image/user-13.jpg" alt="Item Notification">
              </div>
              <div class="v-ntf__item-aside">
                <div class="v-ntf__title">
                  New email from abcNew email from abcNew email from abc
                </div>
                <div class="v-ntf__article">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae possimus ullam delectus enim iste? Voluptas eligendi repellendus alias, beatae fugiat officia natus molestiae iure. Sunt alias quaerat velit eaque eius?
                </div>
                <div class="v-ntf__time">
                  2 hour ago
                </div>
              </div>
            </div>
          </a>
        </li>
        <li>
          <a href="#">
            <div class="v-ntf__items v-row v-justify-content-start v-align-items-start">
              <div class="v-ntf__item">
                <img src="~/assets/image/user-13.jpg" alt="Item Notification">
              </div>
              <div class="v-ntf__item-aside">
                <div class="v-ntf__title">
                  New email from abcNew email from abcNew email from abc
                </div>
                <div class="v-ntf__article">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae possimus ullam delectus enim iste? Voluptas eligendi repellendus alias, beatae fugiat officia natus molestiae iure. Sunt alias quaerat velit eaque eius?
                </div>
                <div class="v-ntf__time">
                  2 hour ago
                </div>
              </div>
            </div>
          </a>
        </li>
        <li>
          <a href="#">
            <div class="v-ntf__items v-row v-justify-content-start v-align-items-start">
              <div class="v-ntf__item">
                <img src="~/assets/image/user-13.jpg" alt="Item Notification">
              </div>
              <div class="v-ntf__item-aside">
                <div class="v-ntf__title">
                  New email from abcNew email from abcNew email from abc
                </div>
                <div class="v-ntf__article">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae possimus ullam delectus enim iste? Voluptas eligendi repellendus alias, beatae fugiat officia natus molestiae iure. Sunt alias quaerat velit eaque eius?
                </div>
                <div class="v-ntf__time">
                  2 hour ago
                </div>
              </div>
            </div>
          </a>
        </li>
        <li>
          <a href="#">
            <div class="v-ntf__items v-row v-justify-content-start v-align-items-start">
              <div class="v-ntf__item">
                <img src="~/assets/image/user-13.jpg" alt="Item Notification">
              </div>
              <div class="v-ntf__item-aside">
                <div class="v-ntf__title">
                  New email from abcNew email from abcNew email from abc
                </div>
                <div class="v-ntf__article">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae possimus ullam delectus enim iste? Voluptas eligendi repellendus alias, beatae fugiat officia natus molestiae iure. Sunt alias quaerat velit eaque eius?
                </div>
                <div class="v-ntf__time">
                  2 hour ago
                </div>
              </div>
            </div>
          </a>
        </li>
        <li>
          <a href="#">
            <div class="v-ntf__items v-row v-justify-content-start v-align-items-start">
              <div class="v-ntf__item">
                <img src="~/assets/image/user-13.jpg" alt="Item Notification">
              </div>
              <div class="v-ntf__item-aside">
                <div class="v-ntf__title">
                  New email from abcNew email from abcNew email from abc
                </div>
                <div class="v-ntf__article">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae possimus ullam delectus enim iste? Voluptas eligendi repellendus alias, beatae fugiat officia natus molestiae iure. Sunt alias quaerat velit eaque eius?
                </div>
                <div class="v-ntf__time">
                  2 hour ago
                </div>
              </div>
            </div>
          </a>
        </li>
      </ul>
      <div class="v-card__footer">
        <a href="#">View More</a>
      </div>
    </div>
  </div>
</template>
<script>
import { storeToRefs } from 'pinia';
import { useLayoutStore } from '~/stores/layout';

export default {
  setup() {
    // Destructuring for state/getter of layout store
    const { toggleNotification, } = storeToRefs(useLayoutStore());

    return {
      toggleNotification,
    };
  }
}
</script>
<style lang="">
  
</style>