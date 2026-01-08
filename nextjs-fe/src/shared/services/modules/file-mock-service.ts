import { MediaFile, Folder, FilterOptions, SortOptions, PaginationState, PaginatedResponse, FileOperationType } from '@/components/common/file-manager/types';
import { v4 as uuidv4 } from 'uuid';

// Initial Mock Data Generation
const generateMockData = () => {
  const folders: Folder[] = [
    { id: 'root', name: 'Root', path: '/' },
    { id: 'folder_1', name: 'Images', path: '/Images', parent_id: 'root' },
    { id: 'folder_2', name: 'Documents', path: '/Documents', parent_id: 'root' },
    { id: 'folder_3', name: 'Videos', path: '/Videos', parent_id: 'root' },
    { id: 'folder_4', name: 'Projects', path: '/Projects', parent_id: 'root' },
    { id: 'folder_1_1', name: '2024', path: '/Images/2024', parent_id: 'folder_1' },
    { id: 'folder_1_2', name: '2025', path: '/Images/2025', parent_id: 'folder_1' },
  ];

  const files: MediaFile[] = [
    {
      id: 'file_1',
      drive_id: 'drive_1',
      name: 'Project Specs.pdf',
      mime_type: 'application/pdf',
      url: '#',
      folder_path: '/Documents',
      parent_id: 'folder_2',
      size: 2500000,
      owner_id: 'user_1',
      created_at: new Date('2024-01-15').toISOString(),
      updated_at: new Date('2024-01-15').toISOString(),
      type: 'file',
    },
    {
      id: 'file_2',
      drive_id: 'drive_2',
      name: 'Design System.fig',
      mime_type: 'application/octet-stream',
      url: '#',
      folder_path: '/Projects',
      parent_id: 'folder_4',
      size: 15000000,
      owner_id: 'user_1',
      created_at: new Date('2024-02-01').toISOString(),
      updated_at: new Date('2024-02-10').toISOString(),
      type: 'file',
    },
    {
      id: 'file_3',
      drive_id: 'drive_3',
      name: 'Demo Video.mp4',
      mime_type: 'video/mp4',
      url: 'https://www.w3schools.com/html/mov_bbb.mp4',
      folder_path: '/Videos',
      parent_id: 'folder_3',
      size: 50000000,
      owner_id: 'user_1',
      created_at: new Date('2024-03-05').toISOString(),
      updated_at: new Date('2024-03-05').toISOString(),
      type: 'file',
    },
    {
      id: 'file_4',
      drive_id: 'drive_4',
      name: 'Banner.jpg',
      mime_type: 'image/jpeg',
      url: 'https://images.unsplash.com/photo-1557683316-973673baf926?w=500&h=300&fit=crop',
      folder_path: '/Images/2024',
      parent_id: 'folder_1_1',
      size: 1200000,
      owner_id: 'user_1',
      created_at: new Date('2024-03-10').toISOString(),
      updated_at: new Date('2024-03-10').toISOString(),
      type: 'file',
    },
    {
      id: 'file_5',
      drive_id: 'drive_5',
      name: 'Logo.png',
      mime_type: 'image/png',
      url: 'https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?w=500&h=500&fit=crop',
      folder_path: '/Images/2024',
      parent_id: 'folder_1_1',
      size: 500000,
      owner_id: 'user_1',
      created_at: new Date('2024-03-12').toISOString(),
      updated_at: new Date('2024-03-12').toISOString(),
      type: 'file',
    },
  ];

  // Generate more mock files
  for (let i = 0; i < 50; i++) {
    files.push({
      id: `mock_file_${i}`,
      drive_id: `drive_mock_${i}`,
      name: `Mock Image ${i}.jpg`,
      mime_type: 'image/jpeg',
      url: `https://images.unsplash.com/photo-${1500000000000 + i}?w=500&h=500&fit=crop`,
      folder_path: '/Images/2025',
      parent_id: 'folder_1_2',
      size: Math.floor(Math.random() * 5000000) + 100000,
      owner_id: 'user_1',
      created_at: new Date(Date.now() - Math.floor(Math.random() * 10000000000)).toISOString(),
      updated_at: new Date().toISOString(),
      type: 'file',
    });
  }

  return { folders, files };
};

class FileMockService {
  private folders: Folder[];
  private files: MediaFile[];

  constructor() {
    const data = generateMockData();
    this.folders = data.folders;
    this.files = data.files;
  }

  async getFiles(
    path: string,
    pagination: PaginationState,
    filterOptions: FilterOptions,
    sortOptions: SortOptions,
    searchQuery: string
  ): Promise<PaginatedResponse<MediaFile>> {
    // Simulate network delay
    await new Promise((resolve) => setTimeout(resolve, 500));

    let currentFolderId = 'root';
    if (path !== '/') {
      const folder = this.folders.find((f) => f.path === path);
      currentFolderId = folder ? folder.id : 'root';
    }

    // 1. Filter by folder (unless searching)
    let result = searchQuery
      ? [...this.files]
      : this.files.filter((f) => f.parent_id === currentFolderId);

    // Add subfolders to result if not searching and on first page
    if (!searchQuery && pagination.page === 1) {
      const subfolders = this.folders
        .filter((f) => f.parent_id === currentFolderId)
        .map((f) => ({
          id: f.id,
          drive_id: `folder_${f.id}`,
          name: f.name,
          mime_type: 'application/vnd.google-apps.folder',
          url: '',
          folder_path: f.path,
          parent_id: f.parent_id,
          size: 0,
          owner_id: 'system',
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
          type: 'folder' as const,
        }));
      result = [...subfolders, ...result];
    }

    // 2. Search
    if (searchQuery) {
      const query = searchQuery.toLowerCase();
      result = result.filter((f) => f.name.toLowerCase().includes(query));
    }

    // 3. Filter options
    if (filterOptions.type !== 'all') {
      result = result.filter((f) => {
        if (filterOptions.type === 'folders') return f.type === 'folder';
        if (filterOptions.type === 'images') return f.mime_type.startsWith('image/');
        if (filterOptions.type === 'videos') return f.mime_type.startsWith('video/');
        if (filterOptions.type === 'documents')
          return !f.mime_type.startsWith('image/') && !f.mime_type.startsWith('video/') && f.type !== 'folder';
        return true;
      });
    }

    if (filterOptions.dateFrom) {
      result = result.filter((f) => new Date(f.created_at) >= filterOptions.dateFrom!);
    }
    if (filterOptions.dateTo) {
      result = result.filter((f) => new Date(f.created_at) <= filterOptions.dateTo!);
    }

    // 4. Sort
    result.sort((a, b) => {
      let comparison = 0;
      switch (sortOptions.field) {
        case 'name':
          comparison = a.name.localeCompare(b.name);
          break;
        case 'size':
          comparison = a.size - b.size;
          break;
        case 'date':
          comparison = new Date(a.created_at).getTime() - new Date(b.created_at).getTime();
          break;
        case 'type':
          comparison = a.mime_type.localeCompare(b.mime_type);
          break;
      }
      return sortOptions.order === 'asc' ? comparison : -comparison;
    });

    // 5. Pagination
    const total = result.length;
    const totalPages = Math.ceil(total / pagination.pageSize);
    const start = (pagination.page - 1) * pagination.pageSize;
    const end = start + pagination.pageSize;
    const paginatedData = result.slice(start, end);

    return {
      data: paginatedData,
      pagination: {
        ...pagination,
        total,
        totalPages,
      },
    };
  }

  async createFolder(name: string, parentPath: string): Promise<Folder> {
    await new Promise((resolve) => setTimeout(resolve, 300));
    
    const parent = this.folders.find(f => f.path === parentPath) || this.folders[0];
    const newFolder: Folder = {
      id: `folder_${uuidv4()}`,
      name,
      path: parentPath === '/' ? `/${name}` : `${parentPath}/${name}`,
      parent_id: parent.id,
    };
    
    this.folders.push(newFolder);
    return newFolder;
  }

  async uploadFile(file: File, parentPath: string, onProgress?: (progress: number) => void): Promise<MediaFile> {
    // Simulate upload progress
    for (let i = 0; i <= 100; i += 10) {
      await new Promise((resolve) => setTimeout(resolve, 100));
      onProgress?.(i);
    }

    const parent = this.folders.find(f => f.path === parentPath) || this.folders[0];
    const newFile: MediaFile = {
      id: `file_${uuidv4()}`,
      drive_id: `drive_${uuidv4()}`,
      name: file.name,
      mime_type: file.type,
      url: URL.createObjectURL(file), // Local preview URL
      folder_path: parentPath,
      parent_id: parent.id,
      size: file.size,
      owner_id: 'current_user',
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
      type: 'file',
    };

    this.files.push(newFile);
    return newFile;
  }

  async renameFile(id: string, newName: string): Promise<void> {
    await new Promise((resolve) => setTimeout(resolve, 300));
    const file = this.files.find(f => f.id === id);
    if (file) {
      file.name = newName;
      file.updated_at = new Date().toISOString();
    } else {
      const folder = this.folders.find(f => f.id === id);
      if (folder) {
        folder.name = newName;
        // Note: In a real app, we'd need to update paths of all children
      }
    }
  }

  async deleteFiles(ids: string[]): Promise<void> {
    await new Promise((resolve) => setTimeout(resolve, 300));
    this.files = this.files.filter(f => !ids.includes(f.id));
    this.folders = this.folders.filter(f => !ids.includes(f.id));
  }

  async moveFiles(ids: string[], targetPath: string): Promise<void> {
    await new Promise((resolve) => setTimeout(resolve, 300));
    const targetFolder = this.folders.find(f => f.path === targetPath);
    if (!targetFolder) throw new Error('Target folder not found');

    this.files.forEach(f => {
      if (ids.includes(f.id)) {
        f.parent_id = targetFolder.id;
        f.folder_path = targetPath;
      }
    });
    
    // Handle moving folders logic would go here
  }

  async copyFiles(ids: string[], targetPath: string): Promise<void> {
    await new Promise((resolve) => setTimeout(resolve, 300));
    const targetFolder = this.folders.find(f => f.path === targetPath);
    if (!targetFolder) throw new Error('Target folder not found');

    const filesToCopy = this.files.filter(f => ids.includes(f.id));
    
    filesToCopy.forEach(f => {
      const newFile = { ...f, id: `file_${uuidv4()}`, drive_id: `drive_${uuidv4()}`, parent_id: targetFolder.id, folder_path: targetPath, created_at: new Date().toISOString(), updated_at: new Date().toISOString() };
      this.files.push(newFile);
    });
  }

  async getFolders(): Promise<Folder[]> {
    await new Promise((resolve) => setTimeout(resolve, 300));
    return this.folders;
  }
}

export const fileService = new FileMockService();
