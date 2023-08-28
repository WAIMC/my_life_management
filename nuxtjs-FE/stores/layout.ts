import { defineStore } from "pinia";
import { MenuSidebarsConstant } from '~/constants/menu';
import { TreeMenu } from '~/types/menu-sidebars';

export const useLayoutStore = defineStore("layout", {
  state: () => {
    return {
      toggleBar: false,
      toggleNotification: false,
      toggleProfile: false,
      menus: MenuSidebarsConstant,
    };
  },
  actions: {
    /**
     * Set value for toggle bar state
     */
    setToggleBar () {
      this.toggleBar = !this.toggleBar;
    },

    /**
     * Set value for toggle bar state
     */
    setToggleNotification () {
      const currentValue = this.toggleNotification
      this.resetToggleHeader();
      this.toggleNotification = !currentValue;
    },

    /**
     * Set value for toggle bar state
     */
    setToggleProfile () {
      const currentValue = this.toggleProfile
      this.resetToggleHeader();
      this.toggleProfile = !currentValue;
    },

    /**
     * Reset toggle header
     */
    resetToggleHeader () {
      this.toggleNotification = false;
      this.toggleProfile = false;
    },

    /**
     * Toggle menu sidebars
     * @params string id
     */
    toggleItemMenu (id:string) {
      this.menus.forEach((item1, id1) => {
        if (item1.id === id.substring(0, 5) && item1.items) {
          // Case toggle menu level 1
          if (id.length <= 5) {
            this.menus[id1].isShow = !item1.isShow;

            // Equivalent to a break
            return false;
          }

          item1.items.forEach((item2, id2) => {
            // Case toggle menu level 2
            if (item2.id === id.substring(0, 7)) {
              if ((item2 as any).items) {
                (this.menus[id1].items as any)[id2].isShow = !(item2 as any).isShow;
                
                return false
              }

              // Equivalent to a break
              return false;
            }
          })

          return false;
        }
      })
    },
  },
});
