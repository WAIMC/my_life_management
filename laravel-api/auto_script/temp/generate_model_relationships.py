#!/usr/bin/env python3
"""
Script to automatically add Eloquent relationships to Laravel models
based on schema.json and relationship mappings.
"""

import json
import re
from pathlib import Path
from typing import Dict, List, Tuple

# Define relationship mappings
RELATIONSHIPS = {
    # Master Models
    'admin_mst': {
        'belongsToMany': [
            ('roles', 'RoleMst', 'admin_role_mst', 'admin_mst_id', 'role_mst_id'),
            ('departments', 'DepartmentMst', 'admin_department_mst', 'admin_mst_id', 'department_mst_id'),
        ],
        'hasMany': [
            ('history', 'AdminMstHist', 'admin_mst_id', 'History\\Master'),
        ],
    },
    'role_mst': {
        'belongsToMany': [
            ('admins', 'AdminMst', 'admin_role_mst', 'role_mst_id', 'admin_mst_id'),
            ('apis', 'ApiMst', 'api_role_mst', 'role_mst_id', 'api_mst_id'),
        ],
        'hasMany': [
            ('history', 'RoleMstHist', 'role_mst_id', 'History\\Master'),
        ],
    },
    'department_mst': {
        'belongsToMany': [
            ('admins', 'AdminMst', 'admin_department_mst', 'department_mst_id', 'admin_mst_id'),
            ('policies', 'PolicyDepartmentMst', 'department_management_mst', 'department_mst_id', 'policy_department_mst_id'),
        ],
        'hasMany': [
            ('history', 'DepartmentMstHist', 'department_mst_id', 'History\\Master'),
        ],
    },
    'api_mst': {
        'belongsTo': [
            ('feature', 'FeatureMst', 'feature_mst_id'),
        ],
        'belongsToMany': [
            ('roles', 'RoleMst', 'api_role_mst', 'api_mst_id', 'role_mst_id'),
        ],
        'hasMany': [
            ('history', 'ApiMstHist', 'api_mst_id', 'History\\Master'),
        ],
    },
    'feature_mst': {
        'hasMany': [
            ('apis', 'ApiMst', 'feature_mst_id', 'Master'),
        ],
    },
    'translation_mst': {
        'belongsTo': [
            ('originalTranslator', 'OriginalTranslatorMst', 'original_translator_mst_id'),
        ],
        'belongsToMany': [
            ('languages', 'LanguageMst', 'translation_language_mst', 'translation_mst_id', 'language_mst_id'),
        ],
        'hasMany': [
            ('history', 'TranslationMstHist', 'translation_mst_id', 'History\\Master'),
        ],
    },
    'language_mst': {
        'belongsToMany': [
            ('translations', 'TranslationMst', 'translation_language_mst', 'language_mst_id', 'translation_mst_id'),
        ],
        'hasMany': [
            ('history', 'LanguageMstHist', 'language_mst_id', 'History\\Master'),
        ],
    },
    'original_translator_mst': {
        'hasMany': [
            ('translations', 'TranslationMst', 'original_translator_mst_id', 'Master'),
            ('history', 'OriginalTranslatorMstHist', 'original_translator_mst_id', 'History\\Master'),
        ],
    },
    'policy_department_mst': {
        'belongsToMany': [
            ('departments', 'DepartmentMst', 'department_management_mst', 'policy_department_mst_id', 'department_mst_id'),
        ],
        'hasMany': [
            ('history', 'PolicyDepartmentMstHist', 'policy_department_mst_id', 'History\\Master'),
        ],
    },
    # Management Models
    'category_mgmt': {
        'belongsTo': [
            ('parent', 'CategoryMgmt', 'parent_id'),
        ],
        'hasMany': [
            ('children', 'CategoryMgmt', 'parent_id', 'Management'),
            ('products', 'ProductMgmt', 'category_mgmt_id', 'Management'),
            ('history', 'CategoryMgmtHist', 'category_mgmt_id', 'History\\Management'),
        ],
        'belongsToMany': [
            ('skills', 'SkillMgmt', 'category_skill_mgmt', 'category_mgmt_id', 'skill_mgmt_id'),
        ],
    },
    'product_mgmt': {
        'belongsTo': [
            ('category', 'CategoryMgmt', 'category_mgmt_id'),
        ],
        'hasMany': [
            ('history', 'ProductMgmtHist', 'product_mgmt_id', 'History\\Management'),
        ],
    },
    'skill_mgmt': {
        'hasMany': [
            ('descriptions', 'SkillDescriptionMgmt', 'skill_mgmt_id', 'Management'),
            ('history', 'SkillMgmtHist', 'skill_mgmt_id', 'History\\Management'),
        ],
        'belongsToMany': [
            ('categories', 'CategoryMgmt', 'category_skill_mgmt', 'skill_mgmt_id', 'category_mgmt_id'),
        ],
    },
    'skill_description_mgmt': {
        'belongsTo': [
            ('skill', 'SkillMgmt', 'skill_mgmt_id'),
        ],
        'hasMany': [
            ('history', 'SkillDescriptionMgmtHist', 'skill_description_mgmt_id', 'History\\Management'),
        ],
    },
    'banner_mgmt': {
        'hasMany': [
            ('history', 'BannerMgmtHist', 'banner_mgmt_id', 'History\\Management'),
        ],
    },
    'slider_mgmt': {
        'hasMany': [
            ('history', 'SliderMgmtHist', 'slider_mgmt_id', 'History\\Management'),
        ],
    },
    'social_mgmt': {
        'hasMany': [
            ('history', 'SocialMgmtHist', 'social_mgmt_id', 'History\\Management'),
        ],
    },
    'setting_link_mgmt': {
        'hasMany': [
            ('history', 'SettingLinkMgmtHist', 'setting_link_mgmt_id', 'History\\Management'),
        ],
    },
    'user_mgmt': {
        'hasMany': [
            ('history', 'UserMgmtHist', 'user_mgmt_id', 'History\\Management'),
        ],
    },
    # Junction Tables
    'admin_role_mst': {
        'belongsTo': [
            ('admin', 'AdminMst', 'admin_mst_id'),
            ('role', 'RoleMst', 'role_mst_id'),
        ],
    },
    'admin_department_mst': {
        'belongsTo': [
            ('admin', 'AdminMst', 'admin_mst_id'),
            ('department', 'DepartmentMst', 'department_mst_id'),
        ],
    },
    'api_role_mst': {
        'belongsTo': [
            ('api', 'ApiMst', 'api_mst_id'),
            ('role', 'RoleMst', 'role_mst_id'),
        ],
    },
    'department_management_mst': {
        'belongsTo': [
            ('department', 'DepartmentMst', 'department_mst_id'),
            ('policy', 'PolicyDepartmentMst', 'policy_department_mst_id'),
        ],
    },
    'category_skill_mgmt': {
        'belongsTo': [
            ('category', 'CategoryMgmt', 'category_mgmt_id'),
            ('skill', 'SkillMgmt', 'skill_mgmt_id'),
        ],
    },
    'translation_language_mst': {
        'belongsTo': [
            ('translation', 'TranslationMst', 'translation_mst_id'),
            ('language', 'LanguageMst', 'language_mst_id'),
        ],
    },
}

# History models - all have belongsTo to parent
HISTORY_TABLES = [
    'admin_mst_hist', 'role_mst_hist', 'department_mst_hist', 'api_mst_hist',
    'feature_mst_hist', 'language_mst_hist', 'original_translator_mst_hist',
    'policy_department_mst_hist', 'translation_mst_hist',
    'category_mgmt_hist', 'product_mgmt_hist', 'skill_mgmt_hist',
    'skill_description_mgmt_hist', 'banner_mgmt_hist', 'slider_mgmt_hist',
    'social_mgmt_hist', 'setting_link_mgmt_hist', 'user_mgmt_hist',
]


def snake_to_pascal(snake_str: str) -> str:
    """Convert snake_case to PascalCase"""
    return ''.join(word.capitalize() for word in snake_str.split('_'))


def get_model_namespace(table_name: str) -> str:
    """Determine model namespace based on table name"""
    if '_hist' in table_name:
        if '_mst_hist' in table_name:
            return 'App\\Models\\History\\Master'
        elif '_mgmt_hist' in table_name:
            return 'App\\Models\\History\\Management'
    elif '_mst' in table_name:
        return 'App\\Models\\Master'
    elif '_mgmt' in table_name or '_management_mst' in table_name:
        return 'App\\Models\\Management'
    return 'App\\Models'


def generate_belongs_to(method_name: str, related_model: str, foreign_key: str) -> str:
    """Generate belongsTo relationship method"""
    return f'''
    /**
     * Get the {method_name} that owns this record.
     *
     * @return BelongsTo
     */
    public function {method_name}(): BelongsTo
    {{
        return $this->belongsTo({related_model}::class, '{foreign_key}');
    }}'''


def generate_has_many(method_name: str, related_model: str, foreign_key: str) -> str:
    """Generate hasMany relationship method"""
    return f'''
    /**
     * Get the {method_name} for this record.
     *
     * @return HasMany
     */
    public function {method_name}(): HasMany
    {{
        return $this->hasMany({related_model}::class, '{foreign_key}');
    }}'''


def generate_belongs_to_many(method_name: str, related_model: str, pivot_table: str, 
                             foreign_pivot_key: str, related_pivot_key: str) -> str:
    """Generate belongsToMany relationship method"""
    return f'''
    /**
     * Get the {method_name} associated with this record.
     *
     * @return BelongsToMany
     */
    public function {method_name}(): BelongsToMany
    {{
        return $this->belongsToMany(
            {related_model}::class,
            '{pivot_table}',
            '{foreign_pivot_key}',
            '{related_pivot_key}'
        )->withTimestamps();
    }}'''


def generate_relationships_code(table_name: str) -> Tuple[List[str], List[str]]:
    """Generate relationship methods and use statements for a table"""
    relationships_code = []
    use_statements = set()
    
    # Add relationship type use statements
    relation_types = set()
    
    if table_name in RELATIONSHIPS:
        rels = RELATIONSHIPS[table_name]
        
        # BelongsTo relationships
        if 'belongsTo' in rels:
            relation_types.add('BelongsTo')
            for method_name, related_model, foreign_key in rels['belongsTo']:
                relationships_code.append(generate_belongs_to(method_name, related_model, foreign_key))
                # Add use statement for related model
                namespace = get_model_namespace(re.sub(r'([A-Z])', r'_\1', related_model).lower().strip('_'))
                use_statements.add(f"use {namespace}\\{related_model};")
        
        # HasMany relationships
        if 'hasMany' in rels:
            relation_types.add('HasMany')
            for item in rels['hasMany']:
                method_name, related_model, foreign_key, scope = item
                relationships_code.append(generate_has_many(method_name, related_model, foreign_key))
                # Add use statement
                if scope:
                    namespace = f"App\\Models\\{scope}"
                else:
                    namespace = get_model_namespace(re.sub(r'([A-Z])', r'_\1', related_model).lower().strip('_'))
                use_statements.add(f"use {namespace}\\{related_model};")
        
        # BelongsToMany relationships
        if 'belongsToMany' in rels:
            relation_types.add('BelongsToMany')
            for method_name, related_model, pivot, fpk, rpk in rels['belongsToMany']:
                relationships_code.append(generate_belongs_to_many(method_name, related_model, pivot, fpk, rpk))
                # Add use statement
                namespace = get_model_namespace(re.sub(r'([A-Z])', r'_\1', related_model).lower().strip('_'))
                use_statements.add(f"use {namespace}\\{related_model};")
    
    # Add history relationship for non-history tables
    if table_name in HISTORY_TABLES:
        # History tables have belongsTo parent
        relation_types.add('BelongsTo')
        parent_table = table_name.replace('_hist', '')
        parent_model = snake_to_pascal(parent_table)
        foreign_key = f"{parent_table}_id"
        relationships_code.append(generate_belongs_to('parent', parent_model, foreign_key))
        namespace = get_model_namespace(parent_table)
        use_statements.add(f"use {namespace}\\{parent_model};")
    
    # Add use statements for relation types
    for rel_type in relation_types:
        use_statements.add(f"use Illuminate\\Database\\Eloquent\\Relations\\{rel_type};")
    
    return relationships_code, list(use_statements)


def main():
    print("Laravel Model Relationship Generator")
    print("=" * 50)
    
    # Generate summary
    total_tables = len(RELATIONSHIPS) + len(HISTORY_TABLES)
    print(f"\nTotal tables to process: {total_tables}")
    print(f"  - Tables with custom relationships: {len(RELATIONSHIPS)}")
    print(f"  - History tables: {len(HISTORY_TABLES)}")
    
    print("\nRelationship summary:")
    for table, rels in RELATIONSHIPS.items():
        count = sum(len(v) for v in rels.values())
        print(f"  - {table}: {count} relationships")
    
    print("\n" + "=" * 50)
    print("Relationship code generation complete!")
    print("\nNext steps:")
    print("1. Review the generated relationship mappings above")
    print("2. Use this script output to update Laravel models")
    print("3. Test relationships in Laravel Tinker")


if __name__ == '__main__':
    main()
