@include('partials.header')

<div class="bg-white border rounded">
								<div class="row no-gutters">
									<div class="col-lg-6 col-xl-6">
										<div class="profile-content-left pt-5 pb-3 px-3 px-xl-5">
											<div class="card text-center widget-profile px-0 border-0">
												<div class="">
                                                    <img style="height:300px; width:300px;" src="{{$product->image ? asset('dist/img/product_img/'). '/' . $product->image : asset('dist/img/product_img/no-image-found.jpg')}}"/>
												</div>
												<div class="card-body">
													<h4 class="py-2 text-dark">{{$product->name}}</h4>
													<a class="btn btn-primary btn-pill btn-lg  my-4" href="{{route('product.edit',['product' => $product->id])}}">Sửa</a>
												</div>
											</div>
											<div class="d-flex justify-content-between ">
												<div class="text-center pb-4">
													<h6 class="text-dark pb-2">{{$product->price}}</h6>
													<p>Giá</p>
												</div>
                                                <div class="text-center pb-4">
													<p>Tình trạng</p>
                                                    @if($product->status === 'approve')
                                                    <td>
                                                        <span class="badge badge-success">{{$product->status}}</span>
                                                    </td>
                                                    @elseif($product->status === 'pending')
                                                        <td>
                                                            <span class="badge badge-warning">{{$product->status}}</span>
                                                        </td>
                                                    @elseif($product->status === 'reject')
                                                        <td>
                                                            <span class="badge badge-danger">{{$product->status}}</span>
                                                        </td>
                                                    @endif
												</div>
												<div class="text-center pb-4">
													<h6 class="text-dark pb-2">{{$product->quantity}}</h6>
													<p>Số lượng</p>
												</div>

											</div>
											<hr class="w-100">
										</div>
									</div>
									<div class="col-lg-6 col-xl-6">
										<div class="profile-content-right py-5">
											<div class="tab-content px-3 px-xl-5" id="myTabContent">
												<div class="tab-pane fade show active" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
													<div class="media mt-5 profile-timeline-media">
														<div class="media-body">
															<h6 class="mt-0 text-dark">Mô tả</h6>
															<span>Sản phẩm</span>
															<p>{{$product->description}}</p>
														</div>
													</div>

												</div>

											</div>
										</div>
									</div>
								</div>
							</div>

  @include('partials.footer')

