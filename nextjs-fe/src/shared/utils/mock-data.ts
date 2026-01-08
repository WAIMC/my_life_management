/**
 * Mock Data for Testing
 * 
 * This file contains mock data used for testing purposes.
 * When real API integration is complete, this file can be safely deleted.
 * 
 * Usage: Import mock data from this file instead of hardcoding in components.
 * 
 * @deprecated This is temporary mock data for testing only
 */

export interface MockAdmin {
  id: number;
  email: string;
  user_name: string;
  first_name: string;
  last_name: string;
  is_active: boolean;
}

/**
 * Mock admin users for testing
 * TODO: Replace with real API integration
 */
export const mockAdmins: MockAdmin[] = [
  {
    id: 1,
    email: 'root@example.com',
    user_name: 'root',
    first_name: 'Super',
    last_name: 'Admin',
    is_active: true,
  },
  {
    id: 2,
    email: 'admin@example.com',
    user_name: 'admin',
    first_name: 'John',
    last_name: 'Doe',
    is_active: true,
  },
];

// Add more mock data here as needed
// Example:
// export const mockPosts = [...];
// export const mockCategories = [...];
