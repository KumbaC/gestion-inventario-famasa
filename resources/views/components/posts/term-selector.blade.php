@props([
    'name',
    'taxonomyModel',
    'term' => null,
    'parentTerms' => [],
    'placeholder' => __('Select'),
    'label' => null,
    'searchable' => false,
])

@php
    function buildHierarchicalOptions($terms, $parentId = null, $depth = 0, $currentTermId = null) {
        $options = [];
        foreach ($terms as $term) {
            if ($term->parent_id == $parentId && (!$currentTermId || $term->id !== $currentTermId)) {
                $indent = str_repeat('— ', $depth);
                $options[] = [
                    'value' => $term->id,
                    'label' => $indent . $term->name
                ];
                $childOptions = buildHierarchicalOptions($terms, $term->id, $depth + 1, $currentTermId);
                $options = array_merge($options, $childOptions);
            }
        }
        return $options;
    }
    
    $parentOptions = [];
    $hierarchicalOptions = buildHierarchicalOptions($parentTerms, null, 0, $term ? $term->id : null);
    $parentOptions = array_merge($parentOptions, $hierarchicalOptions);
@endphp

<x-inputs.combobox 
    :name="$name"
    :label="$label"
    :placeholder="$placeholder"
    :options="$parentOptions"
    :searchable="$searchable"
    x-model="formData.{{ $name }}"
/>
