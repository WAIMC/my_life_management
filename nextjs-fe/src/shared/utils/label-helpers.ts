import { AdminStatus, Gender, IsActive } from '@/shared/enums';

/**
 * Get gender label from Gender enum
 */
export function getGenderLabel(gender: Gender): string {
  const labels: Record<Gender, string> = {
    [Gender.MALE]: 'Male',
    [Gender.FEMALE]: 'Female',
    [Gender.OTHER]: 'Other',
  };
  return labels[gender] || 'Unknown';
}

/**
 * Get admin status label from AdminStatus enum
 */
export function getAdminStatusLabel(status: AdminStatus): string {
  const labels: Record<AdminStatus, string> = {
    [AdminStatus.ACTIVE]: 'Active',
    [AdminStatus.INACTIVE]: 'Inactive',
    [AdminStatus.WAITING]: 'Waiting',
    [AdminStatus.SUSPENDED]: 'Suspended',
  };
  return labels[status] || 'Unknown';
}

/**
 * Get IsActive status label
 */
export function getActiveStatusLabel(isActive: IsActive): string {
  const labels: Record<IsActive, string> = {
    [IsActive.TRUE]: 'Active',
    [IsActive.FALSE]: 'Inactive',
  };
  return labels[isActive] || 'Unknown';
}
