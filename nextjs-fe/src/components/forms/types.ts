import type {
  AdminMst,
  ApiMst,
  BannerMgmt,
  CategoryMgmt,
  DepartmentMst,
  FeatureMst,
  PolicyDepartmentMst,
  RoleMst,
  SettingLinkMgmt,
  EntryDescriptionMgmt,
  EntryMgmt,
  SliderMgmt,
  SocialMgmt,
  TokenMst,
  UserMgmt,
} from '@/shared/types/api';

export interface AdminFormProps {
  initialData?: AdminMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface ApiFormProps {
  initialData?: ApiMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface BannerFormProps {
  initialData?: BannerMgmt | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface CategoryFormProps {
  initialData?: CategoryMgmt | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface DepartmentFormProps {
  initialData?: DepartmentMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface FeatureFormProps {
  initialData?: FeatureMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface PolicyDepartmentFormProps {
  initialData?: PolicyDepartmentMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface RoleFormProps {
  initialData?: RoleMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface SettingLinkFormProps {
  initialData?: SettingLinkMgmt | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface EntryDescriptionFormProps {
  initialData?: EntryDescriptionMgmt | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface EntryFormProps {
  initialData?: EntryMgmt | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface SliderFormProps {
  initialData?: SliderMgmt | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface SocialFormProps {
  initialData?: SocialMgmt | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface TokenFormProps {
  initialData?: TokenMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export interface UserFormProps {
  initialData?: UserMgmt | null;
  onSuccess: () => void;
  onCancel: () => void;
}
