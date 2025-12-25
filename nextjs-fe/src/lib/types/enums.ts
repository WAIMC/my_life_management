/**
 * Common Enums matching Laravel backend
 */

// Generic Status (Base for most tables)
export enum Status {
  INACTIVE = 0,
  ACTIVE = 1,
}

export const StatusLabels: Record<Status, string> = {
  [Status.INACTIVE]: 'Inactive',
  [Status.ACTIVE]: 'Active',
};

// Gender
export enum Gender {
  MALE = 1,
  FEMALE = 2,
  OTHER = 3,
}

export const GenderLabels: Record<Gender, string> = {
  [Gender.MALE]: 'Male',
  [Gender.FEMALE]: 'Female',
  [Gender.OTHER]: 'Other',
};

// Application Logic Enums
export enum ActionType {
  CREATE = 'create',
  UPDATE = 'update',
  DELETE = 'delete',
}

export enum IsActive {
  INACTIVE = 0,
  ACTIVE = 1,
}

export enum IsDelete {
  FALSE = 0,
  TRUE = 1,
}

export enum TypeOfMethod {
  GET = 'GET',
  POST = 'POST',
  PUT = 'PUT',
  PATCH = 'PATCH',
  DELETE = 'DELETE',
}

// Module Specific Statuses

export enum AdminStatus {
  INACTIVE = 0,
  ACTIVE = 1,
  WAITING = 2,
  SUSPENDED = 3,
}

export const AdminStatusLabels: Record<AdminStatus, string> = {
  [AdminStatus.INACTIVE]: 'Inactive',
  [AdminStatus.ACTIVE]: 'Active',
  [AdminStatus.WAITING]: 'Waiting',
  [AdminStatus.SUSPENDED]: 'Suspended',
};

export enum CategoryStatus {
  INACTIVE = 0,
  ACTIVE = 1,
  ARCHIVED = 2,
}

export const CategoryStatusLabels: Record<CategoryStatus, string> = {
  [CategoryStatus.INACTIVE]: 'Inactive',
  [CategoryStatus.ACTIVE]: 'Active',
  [CategoryStatus.ARCHIVED]: 'Archived',
};

export enum DepartmentStatus {
  INACTIVE = 0,
  ACTIVE = 1,
  DRAFT = 2,
  ARCHIVED = 3,
}

export const DepartmentStatusLabels: Record<DepartmentStatus, string> = {
  [DepartmentStatus.INACTIVE]: 'Inactive',
  [DepartmentStatus.ACTIVE]: 'Active',
  [DepartmentStatus.DRAFT]: 'Draft',
  [DepartmentStatus.ARCHIVED]: 'Archived',
};

export enum FeatureStatus {
  INACTIVE = 0,
  ACTIVE = 1,
  DRAFT = 2,
  ARCHIVED = 3,
}

export const FeatureStatusLabels: Record<FeatureStatus, string> = {
  [FeatureStatus.INACTIVE]: 'Inactive',
  [FeatureStatus.ACTIVE]: 'Active',
  [FeatureStatus.DRAFT]: 'Draft',
  [FeatureStatus.ARCHIVED]: 'Archived',
};

export enum SkillStatus {
  INACTIVE = 0,
  ACTIVE = 1,
  WAITING = 2,
  SUSPENDED = 3,
}

export const SkillStatusLabels: Record<SkillStatus, string> = {
  [SkillStatus.INACTIVE]: 'Inactive',
  [SkillStatus.ACTIVE]: 'Active',
  [SkillStatus.WAITING]: 'Waiting',
  [SkillStatus.SUSPENDED]: 'Suspended',
};

export enum SocialStatus {
  INACTIVE = 0,
  ACTIVE = 1,
  PENDING = 2,
}

export const SocialStatusLabels: Record<SocialStatus, string> = {
  [SocialStatus.INACTIVE]: 'Inactive',
  [SocialStatus.ACTIVE]: 'Active',
  [SocialStatus.PENDING]: 'Pending',
};
