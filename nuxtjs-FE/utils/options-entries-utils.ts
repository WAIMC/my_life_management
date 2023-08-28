import { OrderByTypes } from "~/types/sort-types";

export const sortOrders = (data: any[], ordersBy: OrderByTypes, key: string) => {
  data.sort((a, b) => {
    const param1 = (a as any)[key] ?? (a as any).data[key];
    const param2 = (b as any)[key] ?? (b as any).data[key];

    if (param1 === undefined || param2 === undefined) 
      return 0;

    if (param1 === null)
      if (param2 === null) 
        return 0;
      else 
        return 1 * ordersBy
    else if (param2 === null)
      return -1 * ordersBy

    if (param1 > param2)
      return 1 * ordersBy;
    else if (param1 < param2)
      return -1 * ordersBy
    else
      return 0;
  })
}

export const searchEntries = (
  data: any[],
  key: string,
) => {
  let query = key.toLowerCase();

  return data.filter((row) => {
    return Object.keys(row).some((item) => {
      return String(row[item]).toLowerCase().indexOf(query) > -1
    })
  })
}
