{!! view_render_event('bagisto.admin.cinema.release.images.before', ['release' => $release]) !!}

<accordian title="{{ __('admin::app.cinema.release.images') }}" :active="false">
    <div slot="body">
        {!! view_render_event('bagisto.admin.cinema.release.images.controls.before', ['release' => $release]) !!}

        <div class="control-group {{ $errors->has('images.files.*') ? 'has-error' : '' }}">
            <label>{{ __('admin::app.cinema.release.image') }}</label>

            <release-image></release-image>

            <span
                class="control-error"
                v-text="'{{ $errors->first('images.files.*') }}'">
            </span>

            <span class="control-info mt-10">{{ __('admin::app.cinema.release.image-size') }}</span>
        </div>

        {!! view_render_event('bagisto.admin.cinema.release.images.controls.after', ['release' => $release]) !!}
    </div>
</accordian>

{!! view_render_event('bagisto.admin.cinema.release.images.after', ['release' => $release]) !!}

@push('scripts')
    <script type="text/x-template" id="release-image-template">
        <div>
            <div class="image-wrapper">
                <draggable v-model="items" group="people" @end="onDragEnd">
                    <release-image-item
                        v-for='(image, index) in items'
                        :key='image.id'
                        :image="image"
                        class="draggable"
                        @onRemoveImage="removeImage($event)"
                        @onImageSelected="imageSelected($event)">
                    </release-image-item>
                </draggable>
            </div>

            <label class="btn btn-lg btn-primary" style="display: table; width: auto" @click="createFileType">
                {{ __('admin::app.cinema.release.add-image-btn-title') }}
            </label>
        </div>
    </script>

    <script type="text/x-template" id="release-image-item-template">
        <label class="image-item" v-bind:class="{ 'has-image': imageData.length > 0 }">
            <input
                type="hidden"
                :name="'images[files][' + image.id + ']'"
                v-if="! new_image"/>

            <input
                type="hidden"
                :name="'images[positions][' + image.id + ']'"/>

            <input
                :id="_uid"
                ref="imageInput"
                type="file"
                name="images[files][]"
                accept="image/*"
                multiple="multiple"
                v-validate="'mimes:image/*'"
                @change="addImageView($event)"/>

            <img
                class="preview"
                :src="imageData"
                v-if="imageData.length > 0">

            <label class="remove-image" @click="removeImage()">
                {{ __('admin::app.cinema.release.remove-image-btn-title') }}
            </label>
        </label>
    </script>

    <script>
        Vue.component('release-image', {
            template: '#release-image-template',

            data: function() {
                return {
                    images: @json($release->images) ? @json($release->images) : @json({{old('images')}}),

                    imageCount: 0,

                    items: [],
                }
            },

            computed: {
                finalInputName: function() {
                    return 'images[' + this.image.id + ']';
                }
            },

            created: function() {
                let self = this;

                this.images.forEach(function(image) {
                    self.items.push(image)

                    self.imageCount++;
                });
            },

            methods: {
                createFileType: function() {
                    let self = this;

                    this.imageCount++;

                    this.items.push({'id': 'image_' + this.imageCount});
                },

                removeImage: function(image) {
                    let index = this.items.indexOf(image)

                    Vue.delete(this.items, index);
                },

                imageSelected: function(event) {
                    let self = this;

                    Array.from(event.files).forEach(function(image, index) {
                        if (index) {
                            self.imageCount++;

                            self.items.push({'id': 'image_' + self.imageCount, file: image});
                        }
                    });
                },

                onDragEnd: function() {
                    this.items = this.items.map((item, index) => {
                        item.position = index;

                        return item;
                    });
                },
            }
        });

        Vue.component('release-image-item', {
            template: '#release-image-item-template',

            props: {
                image: {
                    type: Object,
                    required: false,
                    default: null
                },
            },

            data: function() {
                return {
                    imageData: '',

                    new_image: 0
                }
            },

            mounted () {
                if (this.image.id && this.image.url) {
                    this.imageData = this.image.url;
                } else if (this.image.id && this.image.file) {
                    this.readFile(this.image.file);
                }
            },

            computed: {
                finalInputName: function() {
                    return this.inputName + '[' + this.image.id + ']';
                }
            },

            methods: {
                addImageView: function() {
                    let imageInput = this.$refs.imageInput;

                    if (imageInput.files && imageInput.files[0]) {
                        if (imageInput.files[0].type.includes('image/')) {
                            this.readFile(imageInput.files[0])

                            if (imageInput.files.length > 1) {
                                this.$emit('onImageSelected', imageInput)
                            }
                        } else {
                            imageInput.value = "";

                            alert('Only images (.jpeg, .jpg, .png, ..) are allowed.');
                        }
                    }
                },

                readFile: function(image) {
                    let reader = new FileReader();

                    reader.onload = (e) => {
                        this.imageData = e.target.result;
                    }

                    reader.readAsDataURL(image);

                    this.new_image = 1;
                },

                removeImage: function() {
                    this.$emit('onRemoveImage', this.image)
                }
            }
        });
    </script>
@endpush
