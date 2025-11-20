/**
 * Common Enums matching Laravel backend
 */

export enum Status {
  ACTIVE = 1,
  INACTIVE = 2,
}

export enum Gender {
  MALE = 1,
  FEMALE = 2,
  OTHER = 3,
}

export enum ActionType {
  CREATE = 'create',
  UPDATE = 'update',
  DELETE = 'delete',
}

export const StatusLabels: Record<Status, string> = {
  [Status.ACTIVE]: 'Active',
  [Status.INACTIVE]: 'Inactive',
};

export const GenderLabels: Record<Gender, string> = {
  [Gender.MALE]: 'Male',
  [Gender.FEMALE]: 'Female',
  [Gender.OTHER]: 'Other',
};
