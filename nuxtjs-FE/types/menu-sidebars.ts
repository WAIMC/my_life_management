// Menu sidebar 1 level
interface TreeMenuLevelOne {
  id: string,
  label: string,
  route: string,
  icon: string,
}[];

// Menu sidebar 2 level
interface TreeMenuLevelTwo {
  id: string,
  label: string,
  icon: string,
  isShow: boolean,
  items: {
    id: string,
    label: string,
    route: string
  }[]
}[];

// Menu sidebar 3 level
interface TreeMenuLevelThree {
  id: string,
  label: string,
  icon: string,
  isShow: boolean,
  items: {
    id: string,
    label: string,
    isShow: boolean,
    items: {
      id: string,
      label: string,
      route: string
    }[],
  }[],
}[];


export type TreeMenu =
  TreeMenuLevelOne
  | TreeMenuLevelTwo 
  | TreeMenuLevelThree;

// export const MenuSidebarsConstant: ({
//   label: string;
//   route: string;
//   icon: string;
//   items?: undefined;
// } | {
//   label: string;
//   icon: string;
//   items: {
//       label: string;
//       items: {
//           label: string;
//           route: string;
//       }[];
//   }[];
//   route?: undefined;
// } | {
//   label: string;
//   icon: string;
//   items: {
//       ...;
//   }[];
//   route?: undefined;
// })[]