<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Form;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormDateField;
use Code16\Sharp\Form\Fields\SharpFormListField;
use Code16\Sharp\Form\Fields\SharpFormMarkdownField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Fields\SharpFormTextareaField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Layout\FormLayoutFieldset;
use Code16\Sharp\Form\SharpForm;

class FormojFormSharpForm extends SharpForm
{
    use WithSharpFormEloquentUpdater;

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    function buildFormFields()
    {
        $this->addField(
            SharpFormTextField::make("title")
                ->setLabel(trans("formoj::sharp.forms.fields.title.label"))
        )->addField(
            SharpFormCheckField::make("is_title_hidden", trans("formoj::sharp.forms.fields.is_title_hidden.label"))
        )->addField(
            SharpFormMarkdownField::make("description")
                ->setLabel(trans("formoj::sharp.forms.fields.description.label"))
                ->setToolbar([
                    SharpFormMarkdownField::B, SharpFormMarkdownField::I,
                    SharpFormMarkdownField::SEPARATOR,
                    SharpFormMarkdownField::A,
                ])
                ->setHeight(200)
        )->addField(
            SharpFormMarkdownField::make("success_message")
                ->setLabel(trans("formoj::sharp.forms.fields.success_message.label"))
                ->setToolbar([
                    SharpFormMarkdownField::B, SharpFormMarkdownField::I,
                    SharpFormMarkdownField::SEPARATOR,
                    SharpFormMarkdownField::A,
                ])
                ->setHeight(200)
                ->setHelpMessage(trans("formoj::sharp.forms.fields.success_message.help_text"))
        )->addField(
            SharpFormDateField::make("published_at")
                ->setLabel(trans("formoj::sharp.forms.fields.published_at.label"))
                ->setHasTime(true)
                ->setDisplayFormat("DD/MM/YYYY HH:mm")
        )->addField(
            SharpFormDateField::make("unpublished_at")
                ->setLabel(trans("formoj::sharp.forms.fields.unpublished_at.label"))
                ->setHasTime(true)
                ->setDisplayFormat("DD/MM/YYYY HH:mm")
        )->addField(
            SharpFormListField::make("sections")
                ->setLabel(trans("formoj::sharp.forms.fields.sections.label"))
                ->setAddable()->setAddText(trans("formoj::sharp.forms.fields.sections.add_label"))
                ->setRemovable()
                ->setSortable()->setOrderAttribute("order")
                ->addItemField(
                    SharpFormTextField::make("title")
                        ->setLabel(trans("formoj::sharp.forms.fields.sections.fields.title.label"))
                )
                ->addItemField(
                    SharpFormCheckField::make("is_title_hidden", trans("formoj::sharp.forms.fields.sections.fields.is_title_hidden.label"))
                )
                ->addItemField(
                    SharpFormTextareaField::make("description")
                        ->setLabel(trans("formoj::sharp.forms.fields.sections.fields.description.label"))
                        ->setRowCount(3)
                )
        )->addField(
            SharpFormTextField::make("administrator_email")
                ->setLabel(trans("formoj::sharp.forms.fields.administrator_email.label"))
        )->addField(
            SharpFormSelectField::make("notifications_strategy", FormojFormSharpEntityList::notificationStrategies())
                ->setDisplayAsDropdown()
                ->setLabel(trans("formoj::sharp.forms.fields.notifications_strategy.label"))
        );
    }

    /**
     * Build form layout using ->addTab() or ->addColumn()
     *
     * @return void
     */
    function buildFormLayout()
    {
        $this->addColumn(6, function (FormLayoutColumn $column) {
            $column

                ->withFieldset(trans("formoj::sharp.forms.fields.fieldsets.title"), function (FormLayoutFieldset $fieldset) {
                    $fieldset->withSingleField("title")
                        ->withSingleField("is_title_hidden");
                })
                ->withFieldset(trans("formoj::sharp.forms.fields.fieldsets.dates"), function (FormLayoutFieldset $fieldset) {
                    $fieldset->withFields("published_at|6", "unpublished_at|6");
                })
                ->withSingleField("description")
                ->withSingleField("success_message");

        })->addColumn(6, function (FormLayoutColumn $column) {
            $column
                ->withFieldset(trans("formoj::sharp.forms.fields.fieldsets.notifications"), function (FormLayoutFieldset $fieldset) {
                    $fieldset->withSingleField("notifications_strategy")
                        ->withSingleField("administrator_email");
                })
                ->withSingleField("sections", function(FormLayoutColumn $column) {
                    $column
                        ->withSingleField("title")
                        ->withSingleField("is_title_hidden")
                        ->withSingleField("description");
                });
        });
    }

    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @param $id
     * @return array
     */
    function find($id): array
    {
        return $this->transform(Form::with("sections")->findOrFail($id));
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    function update($id, array $data)
    {
        $form = $id ? Form::findOrFail($id) : new Form();

        $this->save($form, $data);

        return $form->id;
    }

    /**
     * @param $id
     */
    function delete($id)
    {
        Form::findOrFail($id)->delete();
    }
}