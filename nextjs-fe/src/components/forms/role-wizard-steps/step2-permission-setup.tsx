'use client';

import { useState, useMemo, useCallback } from 'react';
import { useTranslations } from 'next-intl';
import { useApiData } from '@/shared/hooks/useApiData';
import { Input } from '@/components/ui/input';
import { Checkbox } from '@/components/ui/checkbox';
import { Badge } from '@/components/ui/badge';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { API_ENDPOINTS } from '@/shared/api';
import type { ApiMst, FeatureMst } from '@/shared/types/api';
import { Search } from 'lucide-react';
import { HTTP_METHODS, HTTP_METHOD_LABELS, API_TYPE_TO_METHOD } from '@/shared/config/role-wizard.constant';
import type { Step2PermissionSetupProps, GroupedApisByFeature } from '@/shared/types/role-wizard.types';

export function Step2PermissionSetup({
  selectedApiIds,
  onSelectedApisChange,
}: Step2PermissionSetupProps) {
  const tCommon = useTranslations('common');
  const tWizard = useTranslations('roleWizard');

  const [searchApi, setSearchApi] = useState('');
  const [searchFeature, setSearchFeature] = useState('');
  const [methodFilter, setMethodFilter] = useState<string>('*');

  // Fetch APIs
  const { data: allApis, loading: apisLoading } = useApiData<ApiMst>(
    API_ENDPOINTS.MASTER.API,
    { per_page: 1000 }
  );

  // Fetch Features
  const { data: allFeatures, loading: featuresLoading } = useApiData<FeatureMst>(
    API_ENDPOINTS.MASTER.FEATURE,
    { per_page: 1000 }
  );

  // Group APIs by Feature
  const groupedApis = useMemo(() => {
    const grouped: GroupedApisByFeature = {};

    allFeatures.forEach((feature) => {
      grouped[feature.id] = {
        feature,
        apis: [],
      };
    });

    allApis.forEach((api) => {
      if (grouped[api.feature_mst_id]) {
        grouped[api.feature_mst_id].apis.push(api);
      }
    });

    return grouped;
  }, [allApis, allFeatures]);

  // Filter features and APIs
  const filteredGroupedApis = useMemo(() => {
    const result: GroupedApisByFeature = {};

    Object.entries(groupedApis).forEach(([featureId, { feature, apis }]) => {
      // Filter by feature name search
      if (
        searchFeature &&
        !feature.name.toLowerCase().includes(searchFeature.toLowerCase())
      ) {
        return;
      }

      // Filter APIs
      const filteredApis = apis.filter((api: ApiMst) => {
        // Filter by API search
        if (
          searchApi &&
          !api.name.toLowerCase().includes(searchApi.toLowerCase()) &&
          !api.path.toLowerCase().includes(searchApi.toLowerCase())
        ) {
          return false;
        }

        // Filter by method
        if (methodFilter !== '*' && api.type !== parseInt(methodFilter)) {
          return false;
        }

        return true;
      });

      // Only include feature if it has APIs or matches search
      if (filteredApis.length > 0 || (!searchApi && methodFilter === '*')) {
        result[parseInt(featureId)] = {
          feature,
          apis: filteredApis,
        };
      }
    });

    return result;
  }, [groupedApis, searchApi, searchFeature, methodFilter]);

  // Get highlighted features (those with checked APIs)
  const highlightedFeatures = useMemo(() => {
    const featured = new Set<number>();
    Object.entries(groupedApis).forEach(([featureId, { apis }]) => {
      const hasCheckedApi = apis.some((api: ApiMst) =>
        selectedApiIds.includes(api.id)
      );
      if (hasCheckedApi) {
        featured.add(parseInt(featureId));
      }
    });
    return featured;
  }, [groupedApis, selectedApiIds]);

  // Toggle feature checkbox
  const handleToggleFeature = useCallback(
    (featureId: number) => {
      const featureApis = groupedApis[featureId]?.apis || [];
      const featureApiIds = featureApis.map((api) => api.id);
      const allChecked = featureApiIds.every((id) =>
        selectedApiIds.includes(id)
      );

      if (allChecked) {
        // Uncheck all
        const newSelected = selectedApiIds.filter(
          (id) => !featureApiIds.includes(id)
        );
        onSelectedApisChange(newSelected);
      } else {
        // Check all
        const newSelected = Array.from(
          new Set([...selectedApiIds, ...featureApiIds])
        );
        onSelectedApisChange(newSelected);
      }
    },
    [groupedApis, selectedApiIds, onSelectedApisChange]
  );

  // Toggle API checkbox
  const handleToggleApi = useCallback(
    (apiId: number) => {
      if (selectedApiIds.includes(apiId)) {
        onSelectedApisChange(selectedApiIds.filter((id) => id !== apiId));
      } else {
        onSelectedApisChange([...selectedApiIds, apiId]);
      }
    },
    [selectedApiIds, onSelectedApisChange]
  );

  // Scroll feature into view from right panel
  const scrollFeatureIntoView = useCallback((featureId: number) => {
    const featureElement = document.getElementById(`feature-${featureId}`);
    if (featureElement) {
      featureElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }, []);

  const totalApis = allApis.length;
  const selectedCount = selectedApiIds.length;

  const isLoading = apisLoading || featuresLoading;

  return (
    <div className="w-full flex flex-col space-y-4 overflow-hidden" style={{ height: 'calc(80vh - 280px)' }}>
      {/* Header with count */}
      <div className="flex items-center justify-between bg-gray-50 p-3 rounded-lg border shrink-0">
        <div className="text-sm font-semibold">
          {tWizard('selectedApis')}: <span className="text-primary">{selectedCount}</span> / {totalApis}
        </div>
        <Badge variant={selectedCount > 0 ? 'default' : 'secondary'}>
          {((selectedCount / totalApis) * 100).toFixed(0)}% {tWizard('selected')}
        </Badge>
      </div>

      {/* Main content with dual layout */}
      <div className="flex gap-4 flex-1 border rounded-lg overflow-hidden min-h-0" style={{ minHeight: 0 }}>
        {/* Left panel - Features (30%) */}
        <div className="w-[30%] border-r flex flex-col min-w-0 overflow-hidden">
          {/* Search */}
          <div className="p-3 border-b shrink-0">
            <div className="relative">
              <Search className="absolute left-2 top-2.5 h-4 w-4 text-gray-400" />
              <Input
                placeholder={tWizard('searchFeature')}
                value={searchFeature}
                onChange={(e) => setSearchFeature(e.target.value)}
                className="pl-8 text-sm"
              />
            </div>
          </div>

          {/* Features list */}
          <div className="flex-1 overflow-y-auto min-h-0">
            {isLoading ? (
              <div className="p-4 text-center text-sm text-gray-500">
                {tCommon('loading')}...
              </div>
            ) : (
              <div className="space-y-1 p-2">
                {Object.entries(filteredGroupedApis).map(([featureId, { feature }]) => {
                  const featureApis = groupedApis[parseInt(featureId)]?.apis || [];
                  const isHighlighted = highlightedFeatures.has(parseInt(featureId));

                  return (
                    <button
                      key={feature.id}
                      onClick={() => scrollFeatureIntoView(feature.id)}
                      className={`w-full text-left px-3 py-2 rounded-md text-sm font-medium transition-colors ${
                        isHighlighted
                          ? 'bg-blue-100 text-blue-900'
                          : 'hover:bg-gray-100 text-gray-700'
                      }`}
                    >
                      {feature.name}
                      {isHighlighted && (
                        <Badge className="ml-2 text-xs" variant="default">
                          {featureApis.filter((api) =>
                            selectedApiIds.includes(api.id)
                          ).length}/{featureApis.length}
                        </Badge>
                      )}
                    </button>
                  );
                })}
              </div>
            )}
          </div>
        </div>

        {/* Right panel - APIs (70%) */}
        <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
          {/* Search and Filters */}
          <div className="p-3 border-b space-y-3 shrink-0">
            <div className="relative">
              <Search className="absolute left-2 top-2.5 h-4 w-4 text-gray-400" />
              <Input
                placeholder={tWizard('searchApi')}
                value={searchApi}
                onChange={(e) => setSearchApi(e.target.value)}
                className="pl-8 text-sm"
              />
            </div>
            <div className="flex gap-2">
              <Select value={methodFilter} onValueChange={setMethodFilter}>
                <SelectTrigger className="w-[150px] h-9 text-sm">
                  <SelectValue placeholder={tWizard('filterByMethod')} />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="*">{tWizard('all')}</SelectItem>
                  {Object.values(HTTP_METHODS).map((method) => (
                    <SelectItem key={method} value={method}>
                      {method}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
          </div>

          {/* APIs grouped by feature */}
          <div className="flex-1 overflow-y-auto min-h-0 p-3 space-y-4">
            {isLoading ? (
              <div className="text-center text-sm text-gray-500">
                {tCommon('loading')}...
              </div>
            ) : Object.keys(filteredGroupedApis).length === 0 ? (
              <div className="text-center text-sm text-gray-500 py-8">
                {tWizard('noApis')}
              </div>
            ) : (
              Object.entries(filteredGroupedApis).map(([featureId, { feature, apis }]) => {
                const allChecked = apis.every((api: ApiMst) =>
                  selectedApiIds.includes(api.id)
                );
                const someChecked =
                  !allChecked &&
                  apis.some((api: ApiMst) => selectedApiIds.includes(api.id));

                return (
                  <div
                    key={feature.id}
                    id={`feature-${feature.id}`}
                    className="space-y-2 pb-4 border-b last:border-b-0"
                  >
                    {/* Feature header with checkbox */}
                    <div className="flex items-center gap-2 mb-3">
                      <Checkbox
                        id={`feature-${feature.id}`}
                        checked={allChecked || someChecked}
                        onChange={() => handleToggleFeature(parseInt(featureId))}
                      />
                      <label
                        htmlFor={`feature-${feature.id}`}
                        className="font-semibold text-sm text-gray-700 cursor-pointer"
                      >
                        {feature.name}
                      </label>
                      <Badge variant="secondary" className="text-xs">
                        {apis.length}
                      </Badge>
                    </div>

                    {/* APIs under this feature */}
                    <div className="space-y-2 pl-6">
                      {apis.map((api: ApiMst) => {
                        const methodName = API_TYPE_TO_METHOD[api.type] || HTTP_METHODS.GET;
                        const methodInfo = HTTP_METHOD_LABELS[methodName] || {
                          label: 'UNKNOWN',
                          color: 'bg-gray-100 text-gray-800',
                        };
                        const isChecked = selectedApiIds.includes(api.id);

                        return (
                          <div
                            key={api.id}
                            className="flex items-start gap-3 p-2 rounded-md hover:bg-gray-50 transition-colors"
                          >
                            <Checkbox
                              id={`api-${api.id}`}
                              checked={isChecked}
                              onChange={() => handleToggleApi(api.id)}
                              className="mt-1"
                            />
                            <label
                              htmlFor={`api-${api.id}`}
                              className="flex-1 cursor-pointer flex items-start gap-2"
                            >
                              <div className="flex-1 min-w-0">
                                <div className="flex items-center gap-2 mb-1">
                                  <Badge className={`text-xs ${methodInfo.color}`}>
                                    {methodInfo.label}
                                  </Badge>
                                  <span className="font-semibold text-sm text-gray-900">
                                    {api.name}
                                  </span>
                                </div>
                                <p className="text-xs text-gray-500 break-words">
                                  {api.path}
                                </p>
                              </div>
                            </label>
                          </div>
                        );
                      })}
                    </div>
                  </div>
                );
              })
            )}
          </div>
        </div>
      </div>

      {/* Info box */}
      <div className="bg-amber-50 border border-amber-200 rounded-lg p-4">
        <p className="text-sm text-amber-800">
          {tWizard('step2Info')}
        </p>
      </div>
    </div>
  );
}
