'use client';

import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { FormBuilder, type FormSchema } from '@/components/crud/form-builder';
import { useRouter } from 'next/navigation';
import { toast } from 'react-hot-toast';

const formSchema: FormSchema = {
  layout: 'two-column',
  fields: [
    {
      name: 'title',
      label: 'Title',
      type: 'text',
      required: true,
      placeholder: 'Enter post title',
    },
    {
      name: 'slug',
      label: 'Slug',
      type: 'text',
      required: true,
      placeholder: 'post-slug',
    },
    {
      name: 'author',
      label: 'Author',
      type: 'text',
      required: true,
      placeholder: 'Author name',
    },
    {
      name: 'status',
      label: 'Status',
      type: 'select',
      required: true,
      options: [
        { value: 'draft', label: 'Draft' },
        { value: 'published', label: 'Published' },
      ],
    },
    {
      name: 'content',
      label: 'Content',
      type: 'textarea',
      required: true,
      placeholder: 'Write your post content here...',
    },
  ],
};

export default function CreatePostPage() {
  const router = useRouter();

  const handleSubmit = async (values: Record<string, any>) => {
    try {
      // Simulate API call
      await new Promise((resolve) => setTimeout(resolve, 1000));
      console.log('Form values:', values);
      toast.success('Post created successfully!');
      router.push('/admin/posts');
    } catch (error) {
      toast.error('Failed to create post');
    }
  };

  const handleCancel = () => {
    router.push('/admin/posts');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Create New Post"
        description="Add a new blog post"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Posts', href: '/admin/posts' },
          { label: 'Create', isActive: true },
        ]}
      />

      <div className="mt-6">
        <FormBuilder
          schema={formSchema}
          onSubmit={handleSubmit}
          onCancel={handleCancel}
          submitLabel="Create Post"
        />
      </div>
    </AdminLayout>
  );
}
