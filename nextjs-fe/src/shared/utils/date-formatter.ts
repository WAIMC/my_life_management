/**
 * Date Formatting Utilities
 * Provides standardized date/time formatting functions matching backend Laravel format (d/m/Y)
 */

import { format, parse, parseISO, isValid } from 'date-fns';
import { CommonVal } from '@/shared/config/common';

/**
 * Format a date to display format (dd/MM/yyyy)
 * @param date - Date object, ISO string, or any valid date input
 * @returns Formatted date string in dd/MM/yyyy format
 */
export function formatDate(date: Date | string | null | undefined): string {
  if (!date) return '';
  
  try {
    const dateObj = typeof date === 'string' ? parseISO(date) : date;
    if (!isValid(dateObj)) return '';
    return format(dateObj, CommonVal.DATE_FORMAT);
  } catch {
    return '';
  }
}

/**
 * Format a date to HTML input format (yyyy-MM-dd)
 * @param date - Date object, ISO string, or any valid date input
 * @returns Formatted date string in yyyy-MM-dd format for HTML inputs
 */
export function formatDateForInput(date: Date | string | null | undefined): string {
  if (!date) return '';
  
  try {
    const dateObj = typeof date === 'string' ? parseISO(date) : date;
    if (!isValid(dateObj)) return '';
    return format(dateObj, CommonVal.DATE_INPUT_FORMAT);
  } catch {
    return '';
  }
}

/**
 * Format a date to backend format (dd/MM/yyyy)
 * Use this when sending dates to the Laravel API
 * @param date - Date object, ISO string, or any valid date input
 * @returns Formatted date string in dd/MM/yyyy format for backend
 */
export function formatDateForBackend(date: Date | string | null | undefined): string {
  if (!date) return '';
  
  try {
    const dateObj = typeof date === 'string' ? parseISO(date) : date;
    if (!isValid(dateObj)) return '';
    return format(dateObj, CommonVal.DATE_FORMAT); // dd/MM/yyyy matches Laravel d/m/Y
  } catch {
    return '';
  }
}

/**
 * Format a datetime to display format (dd/MM/yyyy HH:mm:ss)
 * @param date - Date object, ISO string, or any valid date input
 * @returns Formatted datetime string
 */
export function formatDateTime(date: Date | string | null | undefined): string {
  if (!date) return '';
  
  try {
    const dateObj = typeof date === 'string' ? parseISO(date) : date;
    if (!isValid(dateObj)) return '';
    return format(dateObj, CommonVal.DATETIME_FORMAT);
  } catch {
    return '';
  }
}

/**
 * Format a datetime to backend format (dd/MM/yyyy HH:mm:ss)
 * Use this when sending datetimes to the Laravel API
 * @param date - Date object, ISO string, or any valid date input
 * @returns Formatted datetime string for backend
 */
export function formatDateTimeForBackend(date: Date | string | null | undefined): string {
  if (!date) return '';
  
  try {
    const dateObj = typeof date === 'string' ? parseISO(date) : date;
    if (!isValid(dateObj)) return '';
    return format(dateObj, CommonVal.DATETIME_FORMAT); // dd/MM/yyyy HH:mm:ss
  } catch {
    return '';
  }
}

/**
 * Parse a date string from backend format (dd/MM/yyyy) to Date object
 * @param dateString - Date string in dd/MM/yyyy format
 * @returns Date object or null if invalid
 */
export function parseDateFromBackend(dateString: string | null | undefined): Date | null {
  if (!dateString) return null;
  
  try {
    const parsed = parse(dateString, CommonVal.DATE_FORMAT, new Date());
    return isValid(parsed) ? parsed : null;
  } catch {
    return null;
  }
}

/**
 * Parse a datetime string from backend format to Date object
 * @param dateTimeString - DateTime string in dd/MM/yyyy HH:mm:ss format
 * @returns Date object or null if invalid
 */
export function parseDateTimeFromBackend(dateTimeString: string | null | undefined): Date | null {
  if (!dateTimeString) return null;
  
  try {
    const parsed = parse(dateTimeString, CommonVal.DATETIME_FORMAT, new Date());
    return isValid(parsed) ? parsed : null;
  } catch {
    return null;
  }
}

/**
 * Format time only (HH:mm:ss)
 * @param date - Date object, ISO string, or any valid date input
 * @returns Formatted time string
 */
export function formatTime(date: Date | string | null | undefined): string {
  if (!date) return '';
  
  try {
    const dateObj = typeof date === 'string' ? parseISO(date) : date;
    if (!isValid(dateObj)) return '';
    return format(dateObj, CommonVal.TIME_FORMAT);
  } catch {
    return '';
  }
}

/**
 * Check if a date string is valid
 * @param dateString - Date string to validate
 * @returns true if valid, false otherwise
 */
export function isValidDate(dateString: string | null | undefined): boolean {
  if (!dateString) return false;
  
  try {
    const parsed = parseISO(dateString);
    return isValid(parsed);
  } catch {
    return false;
  }
}

/**
 * Get current date formatted for display
 * @returns Current date in dd/MM/yyyy format
 */
export function getCurrentDate(): string {
  return format(new Date(), CommonVal.DATE_FORMAT);
}

/**
 * Get current datetime formatted for display
 * @returns Current datetime in dd/MM/yyyy HH:mm:ss format
 */
export function getCurrentDateTime(): string {
  return format(new Date(), CommonVal.DATETIME_FORMAT);
}
