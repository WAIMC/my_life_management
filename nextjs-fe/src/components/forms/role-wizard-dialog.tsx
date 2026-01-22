'use client';

import { useState, useCallback, useEffect, useLayoutEffect, useRef } from 'react';
import { useTranslations } from 'next-intl';
import { useCrud } from '@/shared/hooks/useCrud';
import { useActionLock } from '@/shared/hooks/useActionLock';
import { handleBindErrors } from '@/shared/utils/error-handler';
import { UI_CONSTANTS } from '@/shared/config';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import type { RoleMst } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import type { RoleFormData } from '@/shared/validation/validation';
import { Step1RoleSetup, Step2PermissionSetup, Step3ReviewConfirm, ConfirmCancelDialog, ConfirmSubmitDialog } from './role-wizard-steps';
import { WIZARD_STEPS } from '@/shared/config/role-wizard.constant';
import type { WizardState, RoleWizardDialogProps } from '@/shared/types/role-wizard.types';

export function RoleWizardDialog({
  open,
  onOpenChange,
  initialData,
  onSuccess,
}: RoleWizardDialogProps) {
  const tCommon = useTranslations('common');
  const tCrud = useTranslations('crud');
  const tWizard = useTranslations('roleWizard');

  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<RoleMst>(ENDPOINTS.MASTER.ROLE);
  const { execute, isLoading: isActionProcessing } = useActionLock({ 
    delay: UI_CONSTANTS.ACTION_DELAY_MS 
  });

  const [wizardState, setWizardState] = useState<WizardState>({
    step: 1,
    roleData: {
      name: '',
      permission: '',
      is_active: true,
    },
    selectedApiIds: [],
  });

  const [showCancelConfirm, setShowCancelConfirm] = useState(false);
  const [showSubmitConfirm, setShowSubmitConfirm] = useState(false);
  const initRef = useRef(false);

  // Initialize wizard state when initialData or open changes
  useLayoutEffect(() => {
    if (!open || initRef.current) return;

    const initState: WizardState = initialData
      ? {
          step: WIZARD_STEPS.ROLE_SETUP,
          roleData: {
            name: initialData.name,
            permission: initialData.permission,
            is_active: initialData.is_active,
          },
          selectedApiIds: initialData.apis?.map((api) => api.id) || [],
        }
      : {
          step: WIZARD_STEPS.ROLE_SETUP,
          roleData: {
            name: '',
            permission: '',
            is_active: true,
          },
          selectedApiIds: [],
        };

    // Use microtask to defer state update
    queueMicrotask(() => {
      initRef.current = true;
      setWizardState(initState);
    });
  }, [open, initialData]);

  // Reset init ref when dialog closes
  useEffect(() => {
    if (!open) {
      initRef.current = false;
    }
  }, [open]);

  const handleStepChange = useCallback((newStep: number) => {
    setWizardState(prev => ({ ...prev, step: newStep }));
  }, []);

  const handleRoleDataChange = useCallback((data: Partial<RoleFormData>) => {
    setWizardState(prev => ({
      ...prev,
      roleData: { ...prev.roleData, ...data },
    }));
  }, []);

  const handleSelectedApisChange = useCallback((apiIds: number[]) => {
    setWizardState(prev => ({
      ...prev,
      selectedApiIds: apiIds,
    }));
  }, []);

  const handleCancel = () => {
    setShowCancelConfirm(true);
  };

  const handleConfirmCancel = () => {
    setShowCancelConfirm(false);
    onOpenChange(false);
    // Reset wizard state
    setWizardState({
      step: WIZARD_STEPS.ROLE_SETUP,
      roleData: {
        name: '',
        permission: '',
        is_active: true,
      },
      selectedApiIds: [],
    });
  };

  const handleNext = () => {
    if (wizardState.step < WIZARD_STEPS.TOTAL_STEPS) {
      handleStepChange(wizardState.step + 1);
    }
  };

  const handlePrev = () => {
    if (wizardState.step > WIZARD_STEPS.ROLE_SETUP) {
      handleStepChange(wizardState.step - 1);
    }
  };

  const handleSkipToReview = () => {
    // Skip from step 2 to step 3
    handleStepChange(WIZARD_STEPS.REVIEW_CONFIRM);
  };

  const handleSubmit = async () => {
    setShowSubmitConfirm(true);
  };

  const handleConfirmSubmit = async () => {
    setShowSubmitConfirm(false);
    await execute(async () => {
      try {
        const submitData = {
          ...wizardState.roleData,
          is_delete: false,
          api_ids: wizardState.selectedApiIds,
        };

        if (isEdit && initialData) {
          await update(initialData.id, submitData);
        } else {
          await create(submitData);
        }
        
        onSuccess();
        onOpenChange(false);
        setWizardState({
          step: WIZARD_STEPS.ROLE_SETUP,
          roleData: {
            name: '',
            permission: '',
            is_active: true,
          },
          selectedApiIds: [],
        });
      } catch (error: unknown) {
        console.error(error);
        handleBindErrors(error, () => {});
      }
    });
  };

  const renderStepContent = () => {
    switch (wizardState.step) {
      case WIZARD_STEPS.ROLE_SETUP:
        return (
          <Step1RoleSetup
            data={wizardState.roleData}
            onChange={handleRoleDataChange}
          />
        );
      case WIZARD_STEPS.PERMISSION_SETUP:
        return (
          <Step2PermissionSetup
            selectedApiIds={wizardState.selectedApiIds}
            onSelectedApisChange={handleSelectedApisChange}
          />
        );
      case WIZARD_STEPS.REVIEW_CONFIRM:
        return (
          <Step3ReviewConfirm
            roleData={wizardState.roleData}
            selectedApiIds={wizardState.selectedApiIds}
            isEdit={isEdit}
          />
        );
      default:
        return null;
    }
  };

  const getStepTitle = () => {
    const titles: Record<number, string> = {
      [WIZARD_STEPS.ROLE_SETUP]: `${tWizard('step')} 1: ${tWizard('roleSetup')}`,
      [WIZARD_STEPS.PERMISSION_SETUP]: `${tWizard('step')} 2: ${tWizard('permissionSetup')}`,
      [WIZARD_STEPS.REVIEW_CONFIRM]: `${tWizard('step')} 3: ${tCommon('review')} & ${tCommon('confirm')}`,
    };
    return titles[wizardState.step] || '';
  };

  const isStep1Valid = wizardState.roleData.name && wizardState.roleData.permission;
  const isLastStep = wizardState.step === WIZARD_STEPS.REVIEW_CONFIRM;
  const isFirstStep = wizardState.step === WIZARD_STEPS.ROLE_SETUP;

  return (
    <>
      <Dialog open={open} onOpenChange={(newOpen) => {
        if (!newOpen) {
          handleCancel();
        }
      }}>
        <DialogContent className="!w-[80vw] !h-[80vh] !max-w-[80vw] !max-h-[80vh] !overflow-hidden !p-6 !gap-0 !left-[50%] !top-[50%] !-translate-x-1/2 !-translate-y-1/2 !rounded-lg !border flex flex-col">
          <div className="flex items-start justify-between mb-4 shrink-0 gap-6">
            <DialogHeader className="flex-1">
              <DialogTitle className="text-2xl">{getStepTitle()}</DialogTitle>
              <DialogDescription className="text-base">
                {wizardState.step === 1 && tCrud('step1Description')}
                {wizardState.step === 2 && tCrud('step2Description')}
                {wizardState.step === 3 && tCrud('step3Description')}
              </DialogDescription>
            </DialogHeader>
            {/* Step indicators - moved to right */}
            <div className="flex gap-2 shrink-0">
              {[WIZARD_STEPS.ROLE_SETUP, WIZARD_STEPS.PERMISSION_SETUP, WIZARD_STEPS.REVIEW_CONFIRM].map((step) => (
                <div key={step} className="flex items-center gap-2">
                  <div
                    className={`flex items-center justify-center w-8 h-8 rounded-full border-2 ${
                      step === wizardState.step
                        ? 'bg-primary text-white border-primary'
                        : step < wizardState.step
                        ? 'bg-green-500 text-white border-green-500'
                        : 'bg-gray-200 text-gray-600 border-gray-300'
                    }`}
                  >
                    {step < wizardState.step ? '✓' : step}
                  </div>
                  {step < WIZARD_STEPS.REVIEW_CONFIRM && (
                    <div
                      className={`w-12 h-1 ${
                        step < wizardState.step ? 'bg-green-500' : 'bg-gray-300'
                      }`}
                    />
                  )}
                </div>
              ))}
            </div>
          </div>

          <div className="flex flex-col flex-1 overflow-hidden min-h-0">
            <div className="text-sm text-gray-600 mb-4 shrink-0">
              {wizardState.step === 1 && tWizard('step1Description')}
              {wizardState.step === 2 && tWizard('step2Description')}
              {wizardState.step === 3 && tWizard('step3Description')}
            </div>
            <div className="flex-1 overflow-y-auto scroll-smooth min-h-0">
              {renderStepContent()}
            </div>
          </div>

          {/* Footer buttons */}
          <div className="flex justify-between items-center pt-6 border-t mt-auto shrink-0">
            <Button
              type="button"
              variant="outline"
              onClick={handleCancel}
              disabled={loading || isActionProcessing}
            >
              {tCommon('cancel')}
            </Button>

            <div className="flex gap-2">
              {!isFirstStep && (
                <Button
                  type="button"
                  variant="outline"
                  onClick={handlePrev}
                  disabled={loading || isActionProcessing}
                >
                  {tCommon('back')}
                </Button>
              )}

              {!isLastStep && wizardState.step === WIZARD_STEPS.PERMISSION_SETUP && (
                <Button
                  type="button"
                  variant="ghost"
                  onClick={handleSkipToReview}
                  disabled={loading || isActionProcessing}
                >
                  {tWizard('skip')}
                </Button>
              )}

              {!isLastStep && (
                <Button
                  type="button"
                  onClick={handleNext}
                  disabled={loading || isActionProcessing || !isStep1Valid}
                >
                  {tCommon('next')}
                </Button>
              )}

              {isLastStep && (
                <Button
                  type="submit"
                  onClick={handleSubmit}
                  disabled={loading || isActionProcessing}
                >
                  {loading || isActionProcessing
                    ? isEdit
                      ? tCommon('updating')
                      : tCommon('creating')
                    : isEdit
                    ? tCommon('update')
                    : tCommon('create')}
                </Button>
              )}
            </div>
          </div>
        </DialogContent>
      </Dialog>

      {/* Confirm Cancel Dialog */}
      <ConfirmCancelDialog
        open={showCancelConfirm}
        onOpenChange={setShowCancelConfirm}
        onConfirm={handleConfirmCancel}
      />

      {/* Confirm Submit Dialog */}
      <ConfirmSubmitDialog
        open={showSubmitConfirm}
        onOpenChange={setShowSubmitConfirm}
        onConfirm={handleConfirmSubmit}
        isLoading={loading || isActionProcessing}
      />
    </>
  );
}
