{!! view_render_event('bagisto.admin.cinema.master.images.before', ['master' => $master]) !!}

<accordian title="{{ __('admin::app.cinema.master.images') }}" :active="false">
    <div slot="body">
        {!! view_render_event('bagisto.admin.cinema.master.images.controls.before', ['master' => $master]) !!}

        <div class="control-group {{ $errors->has('images.files.*') ? 'has-error' : '' }}">
            <label>{{ __('admin::app.cinema.categories.image') }}</label>

            <master-image></master-image>

            <span
                class="control-error"
                v-text="'{{ $errors->first('images.files.*') }}'">
            </span>

            <span class="control-info mt-10">{{ __('admin::app.cinema.master.image-size') }}</span>
        </div>

        {!! view_render_event('bagisto.admin.cinema.master.images.controls.after', ['master' => $master]) !!}
    </div>
</accordian>

{!! view_render_event('bagisto.admin.cinema.master.images.after', ['master' => $master]) !!}

@push('scripts')
    <script type="text/x-template" id="master-image-template">
        <div>
            <div class="image-wrapper">
                <draggable v-model="items" group="people" @end="onDragEnd">
                    <master-image-item
                        v-for='(image, index) in items'
                        :key='image.id'
                        :image="image"
                        class="draggable"
                        @onRemoveImage="removeImage($event)"
                        @onImageSelected="imageSelected($event)">
                    </master-image-item>
                </draggable>
            </div>

            <label class="btn btn-lg btn-primary" style="display: table; width: auto" @click="createFileType">
                {{ __('admin::app.cinema.master.add-image-btn-title') }}
            </label>
        </div>
    </script>

    <script type="text/x-template" id="master-image-item-template">
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
                {{ __('admin::app.cinema.master.remove-image-btn-title') }}
            </label>
        </label>
    </script>

    <script>
        Vue.component('master-image', {
            template: '#master-image-template',

            data: function() {
                return {
                    images: @json($master->images) ? @json($master->images) : @json({{old('images')}}),

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

        Vue.component('master-image-item', {
            template: '#master-image-item-template',

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
