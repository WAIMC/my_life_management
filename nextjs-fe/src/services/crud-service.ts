/**
 * Generic CRUD Service
 * Provides reusable methods for Create, Read, Update, Delete operations
 */

import { apiClient } from '@/lib/api-client';
import type { PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export interface CrudServiceConfig {
  baseUrl: string;
  endpoints?: {
    list?: string;
    get?: string;
    create?: string;
    update?: string;
    delete?: string;
  };
}

export class CrudService<T = any> {
  private baseUrl: string;
  private endpoints: Required<CrudServiceConfig['endpoints']>;

  constructor(config: CrudServiceConfig) {
    this.baseUrl = config.baseUrl;
    this.endpoints = {
      list: config.endpoints?.list ?? '/list',
      get: config.endpoints?.get ?? '',
      create: config.endpoints?.create ?? '/store',
      update: config.endpoints?.update ?? '/update',
      delete: config.endpoints?.delete ?? '/delete',
    };
  }

  /**
   * Get list of items with pagination
   */
  async list(params?: ListQueryParams): Promise<PaginatedResponse<T>> {
    const url = `${this.baseUrl}${this.endpoints!.list}`;
    const response = await apiClient.get<PaginatedResponse<T>>(url, params);
    return response.data;
  }

  /**
   * Get single item by ID
   */
  async getById(id: string | number): Promise<T | null> {
    const response = await this.list({ id, per_page: 1 } as any);
    return response.data[0] || null;
  }

  /**
   * Get single item by ID (alternative method using dedicated endpoint)
   */
  async get(id: string | number): Promise<T> {
    const url = `${this.baseUrl}${this.endpoints!.get}/${id}`;
    const response = await apiClient.get<T>(url);
    return response.data;
  }

  /**
   * Create new item
   */
  async create(data: Partial<T>): Promise<number> {
    const url = `${this.baseUrl}${this.endpoints!.create}`;
    const response = await apiClient.post<number>(url, data);
    return response.data;
  }

  /**
   * Update existing item
   */
  async update(id: string | number, data: Partial<T>): Promise<number> {
    const url = `${this.baseUrl}${this.endpoints!.update}/${id}`;
    const response = await apiClient.put<number>(url, data);
    return response.data;
  }

  /**
   * Delete item by ID
   */
  async delete(id: string | number): Promise<void> {
    const url = `${this.baseUrl}${this.endpoints!.delete}/${id}`;
    await apiClient.delete(url);
  }

  /**
   * Bulk delete items
   */
  async bulkDelete(ids: number[]): Promise<void> {
    const url = `${this.baseUrl}${this.endpoints!.delete}`;
    await apiClient.delete(url, { ids });
  }

  /**
   * Export data to CSV
   */
  async export(params?: ListQueryParams): Promise<Blob> {
    const url = `${this.baseUrl}/export`;
    const response = await fetch(url + '?' + new URLSearchParams(params as any), {
      method: 'GET',
      headers: {
        'Accept': 'text/csv',
      },
    });
    return response.blob();
  }

  /**
   * Import data from file
   */
  async import(file: File): Promise<{ success: number; failed: number; errors?: any[] }> {
    const url = `${this.baseUrl}/import`;
    const formData = new FormData();
    formData.append('file', file);

    const response = await apiClient.post<{ success: number; failed: number; errors?: any[] }>(
      url,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }
    );
    return response.data;
  }
}

/**
 * Create a CRUD service instance for a specific module
 */
export function createCrudService<T = any>(config: CrudServiceConfig): CrudService<T> {
  return new CrudService<T>(config);
}
