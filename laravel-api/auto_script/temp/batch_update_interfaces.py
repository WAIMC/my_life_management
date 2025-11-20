#!/usr/bin/env python3
"""
Batch update Laravel Repository Interfaces to use LengthAwarePaginator
"""

import os
import re
from pathlib import Path

# Base paths
INTERFACE_DIRS = [
    "/mnt/c/Users/Dell/projects/my_life_management/laravel-api/app/Interfaces/Master",
    "/mnt/c/Users/Dell/projects/my_life_management/laravel-api/app/Interfaces/Management",
]

def update_interface_file(file_path):
    """Update a single interface file to use LengthAwarePaginator"""
    
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Skip if already updated
    if 'LengthAwarePaginator' in content:
        print(f"  ✓ Already updated: {file_path.name}")
        return False
    
    # Skip if no list() method
    if 'public function list(array $payload)' not in content:
        print(f"  ⊘ No list() method: {file_path.name}")
        return False
    
    # Replace Collection import with LengthAwarePaginator
    content = re.sub(
        r'use Illuminate\\Support\\Collection;',
        'use Illuminate\\Contracts\\Pagination\\LengthAwarePaginator;',
        content
    )
    
    # Replace list() return type
    content = re.sub(
        r'(\s+\*\s+@return\s+)Collection',
        r'\1LengthAwarePaginator',
        content
    )
    
    content = re.sub(
        r'(public function list\(array \$payload\):\s+)Collection',
        r'\1LengthAwarePaginator',
        content
    )
    
    # Update comment
    content = re.sub(
        r'(\s+\*\s+Get list)\s*\n',
        r'\1 with pagination\n',
        content
    )
    
    # Write back
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"  ✓ Updated: {file_path.name}")
    return True

def main():
    """Main function to batch update all interface files"""
    
    total_updated = 0
    
    for interface_dir in INTERFACE_DIRS:
        interface_path = Path(interface_dir)
        
        if not interface_path.exists():
            # Try WSL path
            interface_path = Path(interface_dir.replace('/mnt/c/', '\\\\wsl.localhost\\Ubuntu\\'))
        
        if not interface_path.exists():
            print(f"Directory not found: {interface_dir}")
            continue
        
        print(f"\nProcessing {interface_path}...")
        
        # Find all *Interface.php files
        interface_files = list(interface_path.glob('*Interface.php'))
        
        for file_path in sorted(interface_files):
            if update_interface_file(file_path):
                total_updated += 1
    
    print(f"\n{'='*60}")
    print(f"Total interfaces updated: {total_updated}")
    print(f"{'='*60}")

if __name__ == '__main__':
    main()
