<?php

namespace FaithGen\Sermons\Http\Controllers;

use FaithGen\SDK\Helpers\CommentHelper;
use FaithGen\Sermons\Http\Requests\CommentRequest;
use FaithGen\Sermons\Http\Requests\CreateRequest;
use FaithGen\Sermons\Http\Requests\GetRequest;
use FaithGen\Sermons\Http\Requests\IndexRequest;
use FaithGen\Sermons\Http\Requests\UpdatePictureRequest;
use FaithGen\Sermons\Http\Requests\UpdateRequest;
use FaithGen\Sermons\Http\Resources\Sermon as SermonResource;
use FaithGen\Sermons\Http\Resources\SermonList as ListResource;
use FaithGen\Sermons\Jobs\MessageFollowers;
use FaithGen\Sermons\Jobs\ProcessImage;
use FaithGen\Sermons\Jobs\S3Upload;
use FaithGen\Sermons\Jobs\UploadImage;
use FaithGen\Sermons\Models\Sermon;
use FaithGen\Sermons\SermonService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use InnoFlash\LaraStart\Helper;
use InnoFlash\LaraStart\Traits\APIResponses;

class SermonController extends Controller
{
    use AuthorizesRequests, ValidatesRequests, APIResponses, DispatchesJobs;
    /**
     * @var SermonService
     */
    private $sermonService;

    public function __construct(SermonService $sermonService)
    {
        $this->sermonService = $sermonService;
    }

    public function create(CreateRequest $request)
    {
        return $this->sermonService->createFromParent($request->validated());
    }

    /**
     * Fetches a list of sermons.
     *
     * @param IndexRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(IndexRequest $request)
    {
        $sermons = auth()->user()
            ->sermons()
            ->latest()
            ->where(fn ($sermon) => $sermon->search(['preacher', 'title', 'preacher'], $request->filter_text))
            ->paginate(Helper::getLimit($request));

        SermonResource::wrap('sermons');

        if ($request->has('full_sermons')) {
            return SermonResource::collection($sermons);
        } else {
            return ListResource::collection($sermons);
        }
    }

    /**
     * Get a single sermon details.
     *
     * @param Sermon $sermon
     * @return SermonResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function view(Sermon $sermon)
    {
        $this->authorize('view', $sermon);

        SermonResource::withoutWrapping();

        return new SermonResource($sermon);
    }

    /**
     * Update preacher image.
     *
     * @param UpdatePictureRequest $request
     * @return mixed
     */
    public function updatePicture(UpdatePictureRequest $request)
    {
        if ($this->sermonService->getSermon()->image()->exists()) {
            try {
                $this->sermonService->deleteFiles($this->sermonService->getSermon());
            } catch (\Exception $e) {
            }
        }

        if ($request->hasImage && $request->has('image')) {
            try {
                MessageFollowers::withChain([
                    new UploadImage($this->sermonService->getSermon(), request('image')),
                    new ProcessImage($this->sermonService->getSermon()),
                    new S3Upload($this->sermonService->getSermon()),
                ])->dispatch($this->sermonService->getSermon());

                return $this->successResponse('Preacher image updated successfully!');
            } catch (\Exception $e) {
                abort(500, $e->getMessage());
            }
        } else {
            try {
                $this->sermonService->getSermon()->image()->delete();

                return $this->successResponse('Preacher image deleted successfully!');
            } catch (\Exception $e) {
                abort(500, $e->getMessage());
            }
        }
    }

    /**
     * Delete sermon.
     *
     * @param GetRequest $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(GetRequest $request)
    {
        $this->authorize('delete', $this->sermonService->getSermon());

        return $this->sermonService->destroy('Sermon deleted');
    }

    /**
     * Update sermon.
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->sermonService->update($request->validated(), 'Sermon updated successfully');
    }

    /**
     * Comment a sermon.
     *
     * @param CommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(CommentRequest $request)
    {
        return CommentHelper::createComment($this->sermonService->getSermon(), $request);
    }

    /**
     * Sermons posted to a sermon.
     *
     * @param Request $request
     * @param Sermon $sermon
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function comments(Request $request, Sermon $sermon)
    {
        $this->authorize('view', $sermon);

        return CommentHelper::getComments($sermon, $request);
    }
}
