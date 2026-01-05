/**
 * Common Enums matching Laravel backend
 */

// Generic Status (Base for most tables) - Updated for Department
export enum Status {
  DRAFT = 0,
  PUBLISHED = 1,
  ARCHIVED = 2,
}

export const StatusLabels: Record<Status, string> = {
  [Status.DRAFT]: 'Draft',
  [Status.PUBLISHED]: 'Published',
  [Status.ARCHIVED]: 'Archived',
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
  CREATE = 1,
  UPDATE = 2,
  DELETE = 3,
}

export enum IsActive {
  FALSE = 0,
  TRUE = 1,
}

export enum IsDelete {
  FALSE = 0,
  TRUE = 1,
}

export enum TypeOfMethod {
  GET = 0,
  POST = 1,
  PUT = 2,
  PATCH = 3,
  DELETE = 4,
}

export const TypeOfMethodLabels: Record<TypeOfMethod, string> = {
  [TypeOfMethod.GET]: 'GET',
  [TypeOfMethod.POST]: 'POST',
  [TypeOfMethod.PUT]: 'PUT',
  [TypeOfMethod.PATCH]: 'PATCH',
  [TypeOfMethod.DELETE]: 'DELETE',
};

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
