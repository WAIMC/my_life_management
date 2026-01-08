'use client';

import { useState } from 'react';
import { FieldRenderer, type FieldConfig } from './field-renderer';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Separator } from '@/components/ui/separator';

export type FormLayout = 'single' | 'two-column' | 'tabs';

export interface FormSection {
  title: string;
  description?: string;
  fields: FieldConfig[];
}

export interface FormSchema {
  title?: string;
  description?: string;
  layout?: FormLayout;
  sections?: FormSection[]; // For tabs layout
  fields?: FieldConfig[]; // For single/two-column layout
}

interface FormBuilderProps {
  schema: FormSchema;
  initialValues?: Record<string, any>;
  onSubmit: (values: Record<string, any>) => void | Promise<void>;
  onCancel?: () => void;
  submitLabel?: string;
  cancelLabel?: string;
  isLoading?: boolean;
  className?: string;
}

export function FormBuilder({
  schema,
  initialValues = {},
  onSubmit,
  onCancel,
  submitLabel = 'Save',
  cancelLabel = 'Cancel',
  isLoading = false,
  className,
}: FormBuilderProps) {
  const [values, setValues] = useState<Record<string, any>>(initialValues);
  const [errors, setErrors] = useState<Record<string, string>>({});

  const handleFieldChange = (fieldName: string, value: any) => {
    setValues((prev) => ({ ...prev, [fieldName]: value }));
    // Clear error when user starts typing
    if (errors[fieldName]) {
      setErrors((prev) => {
        const newErrors = { ...prev };
        delete newErrors[fieldName];
        return newErrors;
      });
    }
  };

  const validateForm = (): boolean => {
    const newErrors: Record<string, string> = {};
    const allFields = schema.fields || schema.sections?.flatMap((s) => s.fields) || [];

    allFields.forEach((field) => {
      if (field.required && !values[field.name]) {
        newErrors[field.name] = `${field.label} is required`;
      }

      // Email validation
      if (field.type === 'email' && values[field.name]) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(values[field.name])) {
          newErrors[field.name] = 'Invalid email address';
        }
      }

      // Number validation
      if (field.type === 'number' && values[field.name]) {
        const numValue = Number(values[field.name]);
        if (field.min !== undefined && numValue < field.min) {
          newErrors[field.name] = `Minimum value is ${field.min}`;
        }
        if (field.max !== undefined && numValue > field.max) {
          newErrors[field.name] = `Maximum value is ${field.max}`;
        }
      }
    });

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!validateForm()) {
      return;
    }

    try {
      await onSubmit(values);
    } catch (error) {
    }
  };

  const renderFields = (fields: FieldConfig[], columns: 1 | 2 = 1) => {
    const gridClass = columns === 2 ? 'grid grid-cols-1 md:grid-cols-2 gap-4' : 'space-y-4';

    return (
      <div className={gridClass}>
        {fields.map((field) => (
          <div key={field.name} className={field.type === 'textarea' && columns === 2 ? 'md:col-span-2' : ''}>
            <FieldRenderer
              field={field}
              value={values[field.name]}
              onChange={(value) => handleFieldChange(field.name, value)}
              error={errors[field.name]}
            />
          </div>
        ))}
      </div>
    );
  };

  const renderSingleColumn = () => {
    if (!schema.fields) return null;
    return renderFields(schema.fields, 1);
  };

  const renderTwoColumn = () => {
    if (!schema.fields) return null;
    return renderFields(schema.fields, 2);
  };

  const renderTabs = () => {
    if (!schema.sections) return null;

    return (
      <Tabs defaultValue={schema.sections[0]?.title} className="w-full">
        <TabsList className="grid w-full" style={{ gridTemplateColumns: `repeat(${schema.sections.length}, 1fr)` }}>
          {schema.sections.map((section) => (
            <TabsTrigger key={section.title} value={section.title}>
              {section.title}
            </TabsTrigger>
          ))}
        </TabsList>
        {schema.sections.map((section) => (
          <TabsContent key={section.title} value={section.title} className="space-y-4">
            {section.description && (
              <p className="text-sm text-muted-foreground">{section.description}</p>
            )}
            {renderFields(section.fields, 2)}
          </TabsContent>
        ))}
      </Tabs>
    );
  };

  const renderFormContent = () => {
    switch (schema.layout) {
      case 'tabs':
        return renderTabs();
      case 'two-column':
        return renderTwoColumn();
      case 'single':
      default:
        return renderSingleColumn();
    }
  };

  return (
    <form onSubmit={handleSubmit} className={className}>
      <Card>
        {(schema.title || schema.description) && (
          <CardHeader>
            {schema.title && <CardTitle>{schema.title}</CardTitle>}
            {schema.description && <CardDescription>{schema.description}</CardDescription>}
          </CardHeader>
        )}
        
        <CardContent className="pt-6">
          {renderFormContent()}
        </CardContent>

        <Separator />

        <CardFooter className="flex justify-end gap-2 pt-6">
          {onCancel && (
            <Button
              type="button"
              variant="outline"
              onClick={onCancel}
              disabled={isLoading}
            >
              {cancelLabel}
            </Button>
          )}
          <Button type="submit" disabled={isLoading}>
            {isLoading ? 'Saving...' : submitLabel}
          </Button>
        </CardFooter>
      </Card>
    </form>
  );
}
